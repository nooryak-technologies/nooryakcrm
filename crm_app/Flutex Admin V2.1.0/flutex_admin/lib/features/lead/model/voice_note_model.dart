class VoiceNotesModel {
  bool? status;
  String? message;
  List<VoiceNote>? data;

  VoiceNotesModel({this.status, this.message, this.data});

  VoiceNotesModel.fromJson(Map<String, dynamic> json) {
    status = json['status'];
    message = json['message'];
    if (json['data'] != null) {
      data = <VoiceNote>[];
      json['data'].forEach((v) {
        data!.add(VoiceNote.fromJson(v));
      });
    }
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> dataMap = <String, dynamic>{};
    dataMap['status'] = status;
    dataMap['message'] = message;
    if (data != null) {
      dataMap['data'] = data!.map((v) => v.toJson()).toList();
    }
    return dataMap;
  }
}

class VoiceNote {
  String? id;
  String? leadId;
  String? senderId;
  String? senderRole;
  String? staffId;
  String? messageType;
  String? message;
  String? timestamp;
  String? senderName;
  String? formattedTime;
  String? relativeTime;
  String? avatarUrl;

  VoiceNote({
    this.id,
    this.leadId,
    this.senderId,
    this.senderRole,
    this.staffId,
    this.messageType,
    this.message,
    this.timestamp,
    this.senderName,
    this.formattedTime,
    this.relativeTime,
    this.avatarUrl,
  });

  VoiceNote.fromJson(Map<String, dynamic> json) {
    id = json['id']?.toString();
    leadId = json['lead_id']?.toString();
    senderId = json['sender_id']?.toString();
    senderRole = json['sender_role']?.toString();
    staffId = json['staff_id']?.toString();
    messageType = json['message_type']?.toString();
    message = json['message']?.toString();
    timestamp = json['timestamp']?.toString();
    senderName = json['sender_name']?.toString();
    formattedTime = json['formatted_time']?.toString();
    relativeTime = json['relative_time']?.toString();
    avatarUrl = json['avatar_url']?.toString();
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = <String, dynamic>{};
    data['id'] = id;
    data['lead_id'] = leadId;
    data['sender_id'] = senderId;
    data['sender_role'] = senderRole;
    data['staff_id'] = staffId;
    data['message_type'] = messageType;
    data['message'] = message;
    data['timestamp'] = timestamp;
    data['sender_name'] = senderName;
    data['formatted_time'] = formattedTime;
    data['relative_time'] = relativeTime;
    data['avatar_url'] = avatarUrl;
    return data;
  }
}
