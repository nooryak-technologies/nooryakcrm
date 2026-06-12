import 'dart:async';
import 'package:audioplayers/audioplayers.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutex_admin/common/components/custom_loader/custom_loader.dart';
import 'package:flutex_admin/common/components/no_data.dart';
import 'package:flutex_admin/core/service/api_service.dart';
import 'package:flutex_admin/core/utils/color_resources.dart';
import 'package:flutex_admin/core/utils/dimensions.dart';
import 'package:flutex_admin/core/utils/style.dart';
import 'package:flutex_admin/features/lead/controller/lead_details_controller.dart';
import 'package:flutex_admin/core/helper/shared_preference_helper.dart';
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

  final TextEditingController _textController = TextEditingController();
  final ScrollController _scrollController = ScrollController();

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
      controller.loadLeadVoiceNotes(widget.id).then((_) => _scrollToBottom());
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
    _textController.dispose();
    _scrollController.dispose();
    super.dispose();
  }

  void _scrollToBottom() {
    if (_scrollController.hasClients) {
      _scrollController.animateTo(
        _scrollController.position.maxScrollExtent,
        duration: const Duration(milliseconds: 300),
        curve: Curves.easeOut,
      );
    }
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
          title: const Text('Send Audio Update'),
          content: Text('Do you want to send this ${_formatDuration(_recordDuration)} recording?'),
          actions: <Widget>[
            TextButton(
              child: const Text('Cancel'),
              onPressed: () {
                Navigator.of(context).pop();
              },
            ),
            ElevatedButton(
              child: const Text('Send'),
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
                _scrollToBottom();
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

  void _sendMessage(LeadDetailsController controller) async {
    final text = _textController.text.trim();
    if (text.isNotEmpty) {
      _textController.clear();
      await controller.sendLeadTextMessage(widget.id, text);
      _scrollToBottom();
    }
  }

  @override
  Widget build(BuildContext context) {
    return GetBuilder<LeadDetailsController>(
      builder: (controller) {
        return Scaffold(
          body: controller.isVoiceNotesLoading
              ? const CustomLoader()
              : Column(
                  children: [
                    Expanded(
                      child: controller.voiceNotesModel.data == null ||
                              controller.voiceNotesModel.data!.isEmpty
                          ? const Center(child: NoDataWidget())
                          : RefreshIndicator(
                              color: Theme.of(context).primaryColor,
                              backgroundColor: Theme.of(context).cardColor,
                              onRefresh: () async {
                                await controller.loadLeadVoiceNotes(widget.id);
                                _scrollToBottom();
                              },
                              child: ListView.builder(
                                controller: _scrollController,
                                padding: const EdgeInsets.all(Dimensions.space10),
                                itemCount: controller.voiceNotesModel.data!.length,
                                itemBuilder: (context, index) {
                                  final note = controller.voiceNotesModel.data![index];
                                  final isCurrent = _playingIndex == index;
                                  final String currentUserId = Get.find<ApiClient>().sharedPreferences.getString(SharedPreferenceHelper.userIdKey) ?? '';
                                  final isMe = note.staffId == currentUserId || note.senderId == currentUserId;
                                  final isAudio = note.messageType == 'voice' || note.messageType == 'audio' || note.message?.endsWith('.wav') == true || note.message?.endsWith('.mp3') == true || note.message?.endsWith('.m4a') == true;

                                  return Align(
                                    alignment: isMe ? Alignment.centerRight : Alignment.centerLeft,
                                    child: Container(
                                      margin: const EdgeInsets.only(bottom: Dimensions.space12),
                                      constraints: BoxConstraints(
                                        maxWidth: MediaQuery.of(context).size.width * 0.85,
                                      ),
                                      child: Card(
                                        color: isMe ? Theme.of(context).primaryColor.withOpacity(0.12) : Theme.of(context).cardColor,
                                        elevation: 1,
                                        margin: EdgeInsets.zero,
                                        shape: RoundedRectangleBorder(
                                          borderRadius: BorderRadius.circular(12),
                                        ),
                                        child: Padding(
                                          padding: const EdgeInsets.all(Dimensions.space12),
                                          child: Column(
                                            crossAxisAlignment: isMe ? CrossAxisAlignment.end : CrossAxisAlignment.start,
                                            children: [
                                              Row(
                                                mainAxisSize: MainAxisSize.min,
                                                children: [
                                                  Text(
                                                    note.senderName ?? 'System',
                                                    style: regularDefault.copyWith(
                                                      fontWeight: FontWeight.w600,
                                                    ),
                                                  ),
                                                  const SizedBox(width: Dimensions.space8),
                                                  Container(
                                                    padding: const EdgeInsets.symmetric(
                                                      horizontal: 6,
                                                      vertical: 2,
                                                    ),
                                                    decoration: BoxDecoration(
                                                      color: ColorResources.colorRed,
                                                      borderRadius: BorderRadius.circular(4),
                                                    ),
                                                    child: Text(
                                                      (note.senderRole ?? 'staff').toUpperCase(),
                                                      style: regularSmall.copyWith(
                                                        color: Colors.white,
                                                        fontWeight: FontWeight.bold,
                                                        fontSize: 9,
                                                      ),
                                                    ),
                                                  ),
                                                ],
                                              ),
                                              const SizedBox(height: Dimensions.space8),
                                              if (isAudio)
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
                                                    mainAxisSize: MainAxisSize.min,
                                                    children: [
                                                      IconButton(
                                                        icon: Icon(
                                                          isCurrent && _isPlaying
                                                              ? Icons.pause_circle_filled
                                                              : Icons.play_circle_filled,
                                                          color: Theme.of(context).primaryColor,
                                                          size: 32,
                                                        ),
                                                        onPressed: () {
                                                          if (note.message != null) {
                                                            _playPause(index, note.message!);
                                                          }
                                                        },
                                                      ),
                                                      const SizedBox(width: Dimensions.space5),
                                                      SizedBox(
                                                        width: 120,
                                                        child: isCurrent
                                                            ? SliderTheme(
                                                                data: SliderTheme.of(context).copyWith(
                                                                  trackHeight: 2.0,
                                                                  thumbShape: const RoundSliderThumbShape(
                                                                    enabledThumbRadius: 5.0,
                                                                  ),
                                                                  overlayShape: const RoundSliderOverlayShape(
                                                                    overlayRadius: 10.0,
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
                                                                height: 2,
                                                                margin: const EdgeInsets.symmetric(horizontal: 8),
                                                                decoration: BoxDecoration(
                                                                  color: ColorResources.colorGrey.withOpacity(0.3),
                                                                  borderRadius: BorderRadius.circular(1),
                                                                ),
                                                              ),
                                                      ),
                                                      const SizedBox(width: Dimensions.space5),
                                                      Text(
                                                        isCurrent
                                                            ? _formatPosition(_position)
                                                            : '00:00',
                                                        style: regularSmall.copyWith(
                                                          color: ColorResources.blueGreyColor,
                                                          fontSize: 11,
                                                        ),
                                                      ),
                                                    ],
                                                  ),
                                                )
                                              else
                                                Text(
                                                  note.message ?? '',
                                                  style: regularDefault,
                                                ),
                                              const SizedBox(height: Dimensions.space5),
                                              Align(
                                                alignment: Alignment.bottomRight,
                                                child: Text(
                                                  note.relativeTime ?? note.formattedTime ?? '',
                                                  style: lightSmall.copyWith(
                                                    color: ColorResources.blueGreyColor,
                                                    fontSize: 10,
                                                  ),
                                                ),
                                              ),
                                            ],
                                          ),
                                        ),
                                      ),
                                    ),
                                  );
                                },
                              ),
                            ),
                    ),
                    if (_isRecording)
                      Container(
                        padding: const EdgeInsets.all(Dimensions.space10),
                        color: Colors.red.withOpacity(0.1),
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            const Icon(Icons.fiber_manual_record, color: Colors.red),
                            const SizedBox(width: 8),
                            Text(
                              'Recording Audio Update... ${_formatDuration(_recordDuration)}',
                              style: regularDefault.copyWith(color: Colors.red, fontWeight: FontWeight.bold),
                            ),
                          ],
                        ),
                      ),
                    Container(
                      padding: const EdgeInsets.all(Dimensions.space10),
                      decoration: BoxDecoration(
                        color: Theme.of(context).cardColor,
                        boxShadow: [
                          BoxShadow(
                            color: Colors.black.withOpacity(0.05),
                            blurRadius: 5,
                            offset: const Offset(0, -2),
                          ),
                        ],
                      ),
                      child: SafeArea(
                        child: Row(
                          children: [
                            GestureDetector(
                              onTap: () {
                                if (_isRecording) {
                                  _stopRecording(controller);
                                } else {
                                  _startRecording();
                                }
                              },
                              child: Container(
                                padding: const EdgeInsets.all(Dimensions.space10),
                                decoration: BoxDecoration(
                                  color: Colors.white,
                                  shape: BoxShape.circle,
                                  border: Border.all(
                                    color: _isRecording ? Colors.red : ColorResources.colorLightGrey,
                                    width: 1,
                                  ),
                                ),
                                child: Icon(
                                  _isRecording ? Icons.stop : Icons.mic,
                                  color: _isRecording ? Colors.red : Colors.redAccent,
                                  size: 24,
                                ),
                              ),
                            ),
                            const SizedBox(width: Dimensions.space10),
                            Expanded(
                              child: Container(
                                padding: const EdgeInsets.symmetric(horizontal: Dimensions.space12),
                                decoration: BoxDecoration(
                                  color: Colors.white,
                                  borderRadius: BorderRadius.circular(24),
                                  border: Border.all(
                                    color: ColorResources.blueColor.withOpacity(0.6),
                                    width: 1,
                                  ),
                                ),
                                child: TextField(
                                  controller: _textController,
                                  decoration: const InputDecoration(
                                    hintText: 'Type your message...',
                                    border: InputBorder.none,
                                    isDense: true,
                                    contentPadding: EdgeInsets.symmetric(vertical: 8),
                                  ),
                                ),
                              ),
                            ),
                            const SizedBox(width: Dimensions.space10),
                            GestureDetector(
                              onTap: () => _sendMessage(controller),
                              child: Container(
                                padding: const EdgeInsets.all(Dimensions.space10),
                                decoration: const BoxDecoration(
                                  color: ColorResources.blueColor,
                                  shape: BoxShape.circle,
                                ),
                                child: const Icon(
                                  Icons.send,
                                  color: Colors.white,
                                  size: 20,
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                  ],
                ),
        );
      },
    );
  }
}
