import 'dart:async';
import 'package:audioplayers/audioplayers.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutex_admin/common/components/custom_loader/custom_loader.dart';
import 'package:flutex_admin/common/components/no_data.dart';
import 'package:flutex_admin/core/service/api_service.dart';
import 'package:flutex_admin/core/utils/color_resources.dart';
import 'package:flutex_admin/core/utils/dimensions.dart';
import 'package:flutex_admin/core/utils/local_strings.dart';
import 'package:flutex_admin/core/utils/style.dart';
import 'package:flutex_admin/features/lead/controller/lead_details_controller.dart';
import 'package:flutex_admin/features/lead/model/voice_note_model.dart';
import 'package:flutex_admin/features/lead/repo/lead_repo.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:record/record.dart';
import 'dart:io' show File;
import 'package:http/http.dart' as http;

class LeadVoiceNotes extends StatefulWidget {
  const LeadVoiceNotes({super.key, required this.id});
  final String id;

  @override
  State<LeadVoiceNotes> createState() => _LeadVoiceNotesState();
}

class _LeadVoiceNotesState extends State<LeadVoiceNotes> {
  late AudioPlayer _audioPlayer;
  late AudioRecorder _audioRecorder;
  
  bool _isRecording = false;
  int _recordDuration = 0;
  Timer? _recordTimer;
  String? _recordedPath;

  int? _playingIndex;
  Duration _position = Duration.zero;
  Duration _duration = Duration.zero;
  bool _isPlaying = false;

  late StreamSubscription _positionSubscription;
  late StreamSubscription _durationSubscription;
  late StreamSubscription _playerStateSubscription;

  @override
  void initState() {
    Get.put(ApiClient(sharedPreferences: Get.find()));
    Get.put(LeadRepo(apiClient: Get.find()));
    final controller = Get.put(LeadDetailsController(leadRepo: Get.find()));
    controller.isVoiceNotesLoading = true;

    _audioPlayer = AudioPlayer();
    _audioRecorder = AudioRecorder();

    _positionSubscription = _audioPlayer.onPositionChanged.listen((p) {
      setState(() => _position = p);
    });

    _durationSubscription = _audioPlayer.onDurationChanged.listen((d) {
      setState(() => _duration = d);
    });

    _playerStateSubscription = _audioPlayer.onPlayerStateChanged.listen((state) {
      setState(() {
        _isPlaying = state == PlayerState.playing;
        if (state == PlayerState.completed) {
          _playingIndex = null;
          _position = Duration.zero;
          _duration = Duration.zero;
        }
      });
    });

    super.initState();

    WidgetsBinding.instance.addPostFrameCallback((timeStamp) {
      controller.loadLeadVoiceNotes(widget.id);
    });
  }

  @override
  void dispose() {
    _positionSubscription.cancel();
    _durationSubscription.cancel();
    _playerStateSubscription.cancel();
    _audioPlayer.dispose();
    _audioRecorder.dispose();
    _recordTimer?.cancel();
    super.dispose();
  }

  // Playback Control
  void _playPause(int index, String url) async {
    if (_playingIndex == index) {
      if (_isPlaying) {
        await _audioPlayer.pause();
      } else {
        await _audioPlayer.resume();
      }
    } else {
      await _audioPlayer.stop();
      setState(() {
        _playingIndex = index;
        _position = Duration.zero;
        _duration = Duration.zero;
      });
      await _audioPlayer.play(UrlSource(url));
    }
  }

  // Recording Control
  Future<void> _startRecording() async {
    try {
      if (await _audioRecorder.hasPermission()) {
        const config = RecordConfig(
          encoder: AudioEncoder.wav,
          sampleRate: 16000,
          numChannels: 1,
        );
        
        await _audioRecorder.start(config, path: '');
        setState(() {
          _isRecording = true;
          _recordDuration = 0;
        });
        _startTimer();
      } else {
        Get.snackbar('Permission Denied', 'Microphone permission is required to record voice notes');
      }
    } catch (e) {
      if (kDebugMode) print(e);
    }
  }

  void _startTimer() {
    _recordTimer?.cancel();
    _recordTimer = Timer.periodic(const Duration(seconds: 1), (Timer t) {
      setState(() {
        _recordDuration++;
      });
    });
  }

  Future<void> _stopRecording(LeadDetailsController controller) async {
    _recordTimer?.cancel();
    final path = await _audioRecorder.stop();
    setState(() {
      _isRecording = false;
      _recordedPath = path;
    });

    if (path != null) {
      _showUploadDialog(controller, path);
    }
  }

  Future<void> _showUploadDialog(LeadDetailsController controller, String path) async {
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Text('Upload Voice Note'),
          content: Text('Do you want to upload this ${_formatDuration(_recordDuration)} recording?'),
          actions: <Widget>[
            TextButton(
              child: const Text('Cancel'),
              onPressed: () {
                Navigator.of(context).pop();
              },
            ),
            ElevatedButton(
              child: const Text('Upload'),
              onPressed: () async {
                Navigator.of(context).pop();
                List<int> bytes;
                if (kIsWeb) {
                  final response = await http.get(Uri.parse(path));
                  bytes = response.bodyBytes;
                } else {
                  bytes = await File(path).readAsBytes();
                }
                String filename = 'voice_${DateTime.now().millisecondsSinceEpoch}.wav';
                await controller.uploadVoiceNote(widget.id, bytes, filename);
              },
            ),
          ],
        );
      },
    );
  }

  String _formatDuration(int seconds) {
    final int minutes = seconds ~/ 60;
    final int remainingSeconds = seconds % 60;
    return '${minutes.toString().padLeft(2, '0')}:${remainingSeconds.toString().padLeft(2, '0')}';
  }

  String _formatPosition(Duration duration) {
    return _formatDuration(duration.inSeconds);
  }

  @override
  Widget build(BuildContext context) {
    return GetBuilder<LeadDetailsController>(
      builder: (controller) {
        return Scaffold(
          body: controller.isVoiceNotesLoading
              ? const CustomLoader()
              : controller.voiceNotesModel.data == null ||
                      controller.voiceNotesModel.data!.isEmpty
                  ? const Center(child: NoDataWidget())
                  : RefreshIndicator(
                      color: Theme.of(context).primaryColor,
                      backgroundColor: Theme.of(context).cardColor,
                      onRefresh: () async {
                        controller.loadLeadVoiceNotes(widget.id);
                      },
                      child: ListView.separated(
                        padding: const EdgeInsets.fromLTRB(
                          Dimensions.space10,
                          Dimensions.space10,
                          Dimensions.space10,
                          100, // Spacing for recording panel
                        ),
                        itemCount: controller.voiceNotesModel.data!.length,
                        separatorBuilder: (context, index) =>
                            const SizedBox(height: Dimensions.space10),
                        itemBuilder: (context, index) {
                          final note = controller.voiceNotesModel.data![index];
                          final isCurrent = _playingIndex == index;

                          return Card(
                            elevation: 0.5,
                            margin: EdgeInsets.zero,
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(Dimensions.cardRadius),
                            ),
                            child: Padding(
                              padding: const EdgeInsets.all(Dimensions.space12),
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Row(
                                    children: [
                                      CircleAvatar(
                                        radius: 18,
                                        backgroundColor: ColorResources.colorGrey,
                                        backgroundImage: note.avatarUrl != null
                                            ? CachedNetworkImageProvider(note.avatarUrl!)
                                            : null,
                                        child: note.avatarUrl == null
                                            ? const Icon(Icons.person, size: 18)
                                            : null,
                                      ),
                                      const SizedBox(width: Dimensions.space10),
                                      Expanded(
                                        child: Column(
                                          crossAxisAlignment: CrossAxisAlignment.start,
                                          children: [
                                            Text(
                                              note.senderName ?? 'System',
                                              style: regularDefault.copyWith(
                                                fontWeight: FontWeight.w600,
                                              ),
                                            ),
                                            const SizedBox(height: Dimensions.space2),
                                            Text(
                                              note.relativeTime ?? note.formattedTime ?? '',
                                              style: lightSmall.copyWith(
                                                color: ColorResources.blueGreyColor,
                                              ),
                                            ),
                                          ],
                                        ),
                                      ),
                                      Container(
                                        padding: const EdgeInsets.symmetric(
                                          horizontal: 8,
                                          vertical: 3,
                                        ),
                                        decoration: BoxDecoration(
                                          color: ColorResources.blueColor.withOpacity(0.1),
                                          borderRadius: BorderRadius.circular(4),
                                        ),
                                        child: Text(
                                          (note.senderRole ?? 'staff').toUpperCase(),
                                          style: regularSmall.copyWith(
                                            color: ColorResources.blueColor,
                                            fontWeight: FontWeight.bold,
                                            fontSize: 10,
                                          ),
                                        ),
                                      ),
                                    ],
                                  ),
                                  const SizedBox(height: Dimensions.space12),
                                  Container(
                                    padding: const EdgeInsets.symmetric(
                                      horizontal: Dimensions.space8,
                                      vertical: Dimensions.space5,
                                    ),
                                    decoration: BoxDecoration(
                                      color: Theme.of(context).scaffoldBackgroundColor,
                                      borderRadius: BorderRadius.circular(8),
                                    ),
                                    child: Row(
                                      children: [
                                        IconButton(
                                          icon: Icon(
                                            isCurrent && _isPlaying
                                                ? Icons.pause_circle_filled
                                                : Icons.play_circle_filled,
                                            color: Theme.of(context).primaryColor,
                                            size: 36,
                                          ),
                                          onPressed: () {
                                            if (note.message != null) {
                                              _playPause(index, note.message!);
                                            }
                                          },
                                        ),
                                        Expanded(
                                          child: isCurrent
                                              ? SliderTheme(
                                                  data: SliderTheme.of(context).copyWith(
                                                    trackHeight: 3.0,
                                                    thumbShape: const RoundSliderThumbShape(
                                                      enabledThumbRadius: 6.0,
                                                    ),
                                                    overlayShape: const RoundSliderOverlayShape(
                                                      overlayRadius: 12.0,
                                                    ),
                                                  ),
                                                  child: Slider(
                                                    min: 0,
                                                    max: _duration.inSeconds.toDouble() > 0
                                                        ? _duration.inSeconds.toDouble()
                                                        : 1.0,
                                                    value: _position.inSeconds.toDouble().clamp(
                                                          0.0,
                                                          _duration.inSeconds.toDouble() > 0
                                                              ? _duration.inSeconds.toDouble()
                                                              : 1.0,
                                                        ),
                                                    onChanged: (value) async {
                                                      await _audioPlayer.seek(Duration(seconds: value.toInt()));
                                                    },
                                                  ),
                                                )
                                              : Container(
                                                  height: 3,
                                                  margin: const EdgeInsets.symmetric(horizontal: 16),
                                                  decoration: BoxDecoration(
                                                    color: ColorResources.colorGrey.withOpacity(0.3),
                                                    borderRadius: BorderRadius.circular(2),
                                                  ),
                                                ),
                                        ),
                                        Text(
                                          isCurrent
                                              ? _formatPosition(_position)
                                              : '00:00',
                                          style: regularSmall.copyWith(
                                            color: ColorResources.blueGreyColor,
                                          ),
                                        ),
                                        const SizedBox(width: Dimensions.space10),
                                      ],
                                    ),
                                  ),
                                ],
                              ),
                            ),
                          );
                        },
                      ),
                    ),
          bottomSheet: Container(
            padding: const EdgeInsets.symmetric(
              horizontal: Dimensions.space20,
              vertical: Dimensions.space15,
            ),
            decoration: BoxDecoration(
              color: Theme.of(context).cardColor,
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withOpacity(0.05),
                  blurRadius: 10,
                  offset: const Offset(0, -3),
                ),
              ],
              borderRadius: const BorderRadius.only(
                topLeft: Radius.circular(20),
                topRight: Radius.circular(20),
              ),
            ),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Row(
                  children: [
                    Icon(
                      _isRecording ? Icons.mic : Icons.mic_none,
                      color: _isRecording ? Colors.red : ColorResources.blueGreyColor,
                    ),
                    const SizedBox(width: Dimensions.space10),
                    Text(
                      _isRecording
                          ? 'Recording... ${_formatDuration(_recordDuration)}'
                          : 'Press record button to start',
                      style: regularDefault.copyWith(
                        color: _isRecording ? Colors.red : ColorResources.blueGreyColor,
                        fontWeight: _isRecording ? FontWeight.bold : FontWeight.normal,
                      ),
                    ),
                  ],
                ),
                GestureDetector(
                  onTap: () {
                    if (_isRecording) {
                      _stopRecording(controller);
                    } else {
                      _startRecording();
                    }
                  },
                  child: Container(
                    padding: const EdgeInsets.all(Dimensions.space12),
                    decoration: BoxDecoration(
                      color: _isRecording ? Colors.red : Theme.of(context).primaryColor,
                      shape: BoxShape.circle,
                    ),
                    child: Icon(
                      _isRecording ? Icons.stop : Icons.mic,
                      color: Colors.white,
                    ),
                  ),
                ),
              ],
            ),
          ),
        );
      },
    );
  }
}
