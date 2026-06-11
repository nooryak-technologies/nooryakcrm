<!-- Leads Audio Update Modal -->
<div class="modal fade" id="leads-chat-modal" tabindex="-1" role="dialog" aria-labelledby="leadsChatModalLabel">
    <div class="modal-dialog modal-lg responsive-chat-modal" role="document">
        <div class="modal-content chat-modal-content">
            <div class="modal-header chat-modal-header">
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.8; font-size: 24px; color: #fff;"><span aria-hidden="true">&times;</span></button>
                <div class="chat-header-title-container">
                    <h4 class="modal-title chat-modal-title" id="leadsChatModalLabel">
                        <i class="fa-solid fa-microphone chat-header-icon"></i>
                        <span>Audio Updates: <strong id="chat-lead-name">Lead</strong></span>
                    </h4>
                    <!-- Admin Thread Scoping Selector -->
                    <div id="admin-chat-filter-wrapper" class="hide" style="margin-top: 10px;">
                        <label class="text-white" style="font-size: 12px; font-weight: normal; margin-right: 8px;">Select Staff Thread:</label>
                        <select id="admin-staff-filter" class="form-control inline-block" style="width: auto; height: 30px; padding: 4px 8px; font-size: 13px; border-radius: 6px; background-color: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.3); outline: none;">
                            <option value="unified" style="color:#333;">Unified Thread (All Staff)</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="modal-body chat-modal-body">
                <!-- Messages List -->
                <div id="chat-messages-wrapper" style="flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 15px;">
                    <!-- Messages will load dynamically here -->
                    <div class="text-center text-muted" style="margin-top: 50px;">
                        <i class="fa-solid fa-spinner fa-spin fa-2x text-primary" style="margin-bottom: 10px;"></i>
                        <p>Loading audio updates...</p>
                    </div>
                </div>

                <!-- Voice Recording Overlay Indicator -->
                <div id="voice-recording-indicator" class="hide" style="padding: 12px 20px; background: linear-gradient(135deg, #ff4d4d, #cc0000); color: white; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #e2e8f0; animation: slideUp 0.25s ease-out;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span class="recording-pulse-dot"></span>
                        <span style="font-weight: 500; font-size: 13px;">Recording Voice Message...</span>
                        <span id="recording-timer" style="font-family: monospace; font-size: 14px; background: rgba(0,0,0,0.2); padding: 2px 6px; border-radius: 4px; font-weight: bold; margin-left: 5px;">00:00</span>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <button type="button" id="cancel-recording-btn" class="btn btn-xs btn-default" style="background: rgba(255,255,255,0.2); border: none; color: white; padding: 4px 10px; border-radius: 4px;"><i class="fa-regular fa-trash-can" style="margin-right: 4px;"></i> Cancel</button>
                        <button type="button" id="stop-send-recording-btn" class="btn btn-xs btn-success" style="padding: 4px 12px; border-radius: 4px; font-weight: 600;"><i class="fa-solid fa-paper-plane" style="margin-right: 4px;"></i> Send Voice</button>
                    </div>
                </div>

                <!-- Input Zone -->
                <div class="chat-input-container">
                    <!-- Custom styled attachment-like button for Microphone -->
                    <button type="button" id="record-voice-btn" class="btn btn-default voice-mic-btn" title="Record Voice Message">
                        <i class="fa-solid fa-microphone text-danger"></i>
                    </button>
                    
                    <input type="text" id="chat-text-input" class="form-control chat-input-field" placeholder="Type a note or record voice message..." style="flex: 1; height: 42px; border-radius: 20px; padding: 10px 20px; border: 1px solid #cbd5e0; outline: none; font-size: 14px; transition: border-color 0.2s;">
                    
                    <button type="button" id="send-chat-btn" class="btn btn-primary send-msg-btn" title="Send Message">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Glassmorphism Header and Visuals */
.chat-modal-content {
    border-radius: 16px !important;
    border: none !important;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
    overflow: hidden !important;
}
.chat-modal-header {
    background: linear-gradient(135deg, #0f172a, #1e293b) !important;
    color: #ffffff !important;
    padding: 18px 24px !important;
    border-bottom: 1px solid #334155 !important;
}
.chat-modal-title {
    color: #ffffff !important;
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    font-size: 18px !important;
    font-weight: 700 !important;
    letter-spacing: -0.02em !important;
}
.chat-header-icon {
    font-size: 20px !important;
    color: #3b82f6 !important;
}
.chat-modal-body {
    padding: 0 !important;
    display: flex !important;
    flex-direction: column !important;
    height: 540px !important;
    background-color: #f8fafc !important;
}
.responsive-chat-modal {
    max-width: 750px !important;
    margin: 30px auto !important;
    width: calc(100% - 40px) !important;
}

@media (max-width: 768px) {
    .responsive-chat-modal {
        margin: 10px auto !important;
        width: calc(100% - 20px) !important;
        max-width: none !important;
    }
    .chat-modal-body {
        height: calc(100vh - 160px) !important;
        min-height: 420px !important;
    }
}

/* Micro-animations */
@keyframes slideUp {
    from { transform: translateY(100%); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}

.chat-message-bubble {
    animation: fadeIn 0.22s ease-out forwards;
    max-width: 70%;
    display: flex;
    flex-direction: column;
    padding: 10px 14px;
    border-radius: 18px;
    position: relative;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.chat-message-left {
    align-self: flex-start !important;
    background-color: #ffffff !important;
    border: 1px solid #f1f5f9 !important;
    border-radius: 16px 16px 16px 4px !important;
    color: #1e293b !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01), 0 2px 4px -1px rgba(0, 0, 0, 0.005) !important;
}

.chat-message-right {
    align-self: flex-end !important;
    background: linear-gradient(135deg, #3b82f6, #2563eb) !important;
    color: #ffffff !important;
    border-radius: 16px 16px 4px 16px !important;
    box-shadow: 0 4px 12px -2px rgba(37, 99, 235, 0.15) !important;
}

/* Labels and Metadata */
.chat-msg-sender {
    font-size: 11px;
    font-weight: 600;
    margin-bottom: 3px;
    opacity: 0.85;
}
.chat-msg-role-tag {
    display: inline-block;
    padding: 1px 5px;
    border-radius: 4px;
    font-size: 9px;
    text-transform: uppercase;
    font-weight: 700;
    margin-left: 5px;
}
.tag-admin {
    background-color: #ef4444;
    color: white;
}
.tag-staff {
    background-color: #10b981;
    color: white;
}

.chat-msg-time {
    font-size: 10px !important;
    margin-top: 6px !important;
    align-self: flex-end !important;
    opacity: 0.75 !important;
}

/* Scrollbar Custom Styling */
#chat-messages-wrapper::-webkit-scrollbar {
    width: 6px;
}
#chat-messages-wrapper::-webkit-scrollbar-track {
    background: transparent;
}
#chat-messages-wrapper::-webkit-scrollbar-thumb {
    background-color: rgba(156, 163, 175, 0.4);
    border-radius: 10px;
}

.chat-input-container {
    padding: 15px !important;
    background-color: #ffffff !important;
    border-top: 1px solid #e2e8f0 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 12px !important;
    width: 100% !important;
    box-sizing: border-box !important;
}

/* Inputs & Mic Buttons */
.voice-mic-btn {
    width: 44px !important;
    height: 44px !important;
    border-radius: 50% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    border: 1px solid #cbd5e1 !important;
    background: #ffffff !important;
    box-shadow: 0 2px 5px rgba(0,0,0,0.04) !important;
    transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1) !important;
    outline: none !important;
}
.voice-mic-btn:hover {
    transform: scale(1.1) !important;
    background: #fef2f2 !important;
    border-color: #fecaca !important;
}
.voice-mic-btn i {
    font-size: 16px !important;
}
.send-msg-btn {
    width: 44px !important;
    height: 44px !important;
    border-radius: 50% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 0 !important;
    background: #2563eb !important;
    border-color: #2563eb !important;
    color: #ffffff !important;
    transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1) !important;
    outline: none !important;
    box-shadow: 0 2px 5px rgba(37, 99, 235, 0.15) !important;
}
.send-msg-btn:hover {
    transform: scale(1.1) !important;
    background: #1d4ed8 !important;
    border-color: #1d4ed8 !important;
}
.chat-input-field {
    border-radius: 22px !important;
    padding: 10px 20px !important;
    border: 1px solid #e2e8f0 !important;
    outline: none !important;
    font-size: 14px !important;
    transition: all 0.2s ease !important;
    height: 44px !important;
}
.chat-input-field:focus {
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12) !important;
}

/* Pulse Animation for Voice Recording */
.recording-pulse-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: white;
    display: inline-block;
    animation: pulse 1s infinite alternate;
}
@keyframes pulse {
    0% { transform: scale(0.85); opacity: 0.5; }
    100% { transform: scale(1.2); opacity: 1; }
}

/* Premium Audio Playback UI Styles */
.custom-audio-player {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
    padding: 8px 14px !important;
    border-radius: 12px !important;
    background: rgba(15, 23, 42, 0.04) !important;
    margin: 6px 0 !important;
    min-width: 240px !important;
    border: 1px solid rgba(15, 23, 42, 0.06) !important;
}
.chat-message-right .custom-audio-player {
    background: rgba(255, 255, 255, 0.16) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
}
.audio-play-btn {
    width: 32px !important;
    height: 32px !important;
    border-radius: 50% !important;
    border: none !important;
    background: #2563eb !important;
    color: #ffffff !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    outline: none !important;
    box-shadow: 0 2px 4px rgba(37, 99, 235, 0.1) !important;
}
.chat-message-right .audio-play-btn {
    background: #ffffff !important;
    color: #2563eb !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05) !important;
}
.audio-play-btn:hover {
    transform: scale(1.12) !important;
    box-shadow: 0 4px 8px rgba(37, 99, 235, 0.18) !important;
}
.chat-message-right .audio-play-btn:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
}
.audio-progress-container {
    flex: 1 !important;
    height: 5px !important;
    background: rgba(15, 23, 42, 0.1) !important;
    border-radius: 9999px !important;
    position: relative !important;
    cursor: pointer !important;
}
.chat-message-right .audio-progress-container {
    background: rgba(255, 255, 255, 0.25) !important;
}
.audio-progress-bar {
    position: absolute !important;
    left: 0 !important;
    top: 0 !important;
    height: 100% !important;
    width: 0% !important;
    background: #2563eb !important;
    border-radius: 9999px !important;
    transition: width 0.1s linear !important;
}
.chat-message-right .audio-progress-bar {
    background: #ffffff !important;
}
.audio-duration {
    font-size: 11px !important;
    font-family: monospace !important;
    opacity: 0.95 !important;
    min-width: 36px !important;
    font-weight: 600 !important;
}

/* ==========================================================================
   Audio Update Header & Cells Center Alignment Fixes
   ========================================================================== */
table.table-leads tbody td:has(.chat-lead-btn) {
    vertical-align: middle !important;
    text-align: center !important;
}

#th-chat,
table.dataTable thead th#th-chat,
table.dataTable thead td#th-chat {
    vertical-align: middle !important;
    text-align: center !important;
    position: relative !important;
    padding-right: 12px !important;
    padding-left: 12px !important;
    padding-top: 10px !important;
    padding-bottom: 10px !important;
}

#th-chat .audio-update-th-container {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 4px !important;
    width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
    position: relative !important;
    float: none !important;
}

#th-chat .audio-update-th-container i {
    font-size: 16px !important;
    margin: 0 !important;
    padding: 0 !important;
    display: inline-block !important;
    position: static !important;
    float: none !important;
}

#th-chat .audio-update-th-container span {
    font-size: 12px !important;
    font-weight: 600 !important;
    white-space: nowrap !important;
    margin: 0 !important;
    padding: 0 !important;
    display: block !important;
}

#th-chat::before,
#th-chat::after,
table.dataTable thead th#th-chat::before,
table.dataTable thead th#th-chat::after,
table.dataTable thead td#th-chat::before,
table.dataTable thead td#th-chat::after {
    display: none !important;
    content: "" !important;
    opacity: 0 !important;
    width: 0 !important;
    height: 0 !important;
}
</style>

<script>
$(function() {
    // Clear datatable saved state to prevent column alignment shifts due to structural changes
    if (typeof(Storage) !== "undefined" && !sessionStorage.getItem('leads_chat_dt_cleared_v4')) {
        for (var i = localStorage.length - 1; i >= 0; i--) {
            var key = localStorage.key(i);
            if (key && key.indexOf('DataTables_leads') > -1) {
                localStorage.removeItem(key);
            }
        }
        sessionStorage.setItem('leads_chat_dt_cleared_v4', '1');
        location.reload();
        return;
    }

    var chatActiveLeadId = null;
    var chatActiveLeadName = "";
    var pollingIntervalId = null;
    var chatLastMessageCount = 0;
    var lastMessageId = 0;
    var isUserAdmin = false;
    var activeAudio = null;
    var activePlayButton = null;
    var activeProgressBar = null;
    var xhrLoadMessages = null;
    
    // Voice recording parameters
    var mediaRecorder = null;
    var audioChunks = [];
    var recordingStartTime = null;
    var recordingTimerInterval = null;

    // Attach click events dynamically to datatable "Chat" button clicks
    $(document).on('click', '.chat-lead-btn', function(e) {
        e.preventDefault();
        var leadId = $(this).data('lead-id');
        var leadName = $(this).data('lead-name');
        
        chatActiveLeadId = leadId;
        chatActiveLeadName = leadName;
        lastMessageId = 0;
        chatLastMessageCount = 0;
        
        $('#chat-lead-name').text(leadName);
        $('#chat-messages-wrapper').html('<div class="text-center text-muted" style="margin-top: 50px;"><i class="fa-solid fa-spinner fa-spin fa-2x text-primary" style="margin-bottom:10px;"></i><p>Loading audio updates...</p></div>');
        
        // Open the modal
        $('#leads-chat-modal').modal('show');
    });

    // Modal show event
    $('#leads-chat-modal').on('shown.bs.modal', function () {
        chatLastMessageCount = 0;
        lastMessageId = 0;
        loadChatMessages(true);
        startPolling();
    });

    // Modal hide event
    $('#leads-chat-modal').on('hidden.bs.modal', function () {
        stopPolling();
        stopVoiceRecording();
        stopCurrentlyPlayingAudio();
        chatActiveLeadId = null;
        lastMessageId = 0;
        chatLastMessageCount = 0;
    });

    // Send Text Message
    $('#send-chat-btn').on('click', function() {
        sendChatMessage();
    });

    // Enter Key Trigger
    $('#chat-text-input').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            sendChatMessage();
        }
    });

    // Admin Staff Selector Change
    $('#admin-staff-filter').on('change', function() {
        chatLastMessageCount = 0;
        lastMessageId = 0;
        $('#chat-messages-wrapper').html('<div class="text-center text-muted" style="margin-top: 50px;"><i class="fa-solid fa-spinner fa-spin fa-2x text-primary" style="margin-bottom:10px;"></i><p>Loading audio updates...</p></div>');
        loadChatMessages(true);
    });

    // Load messages from backend
    function loadChatMessages(shouldScroll = false) {
        if (!chatActiveLeadId) return;

        var filterStaffId = $('#admin-staff-filter').val();

        var postData = {
            lead_id: chatActiveLeadId,
            staff_id: filterStaffId
        };
        if (lastMessageId > 0) {
            postData.last_message_id = lastMessageId;
        }
        if (typeof csrfData !== 'undefined') {
            postData[csrfData.token_name] = csrfData.hash;
        }

        // Abort any pending load messages request to prevent concurrency race conditions
        if (xhrLoadMessages) {
            xhrLoadMessages.abort();
        }

        xhrLoadMessages = $.ajax({
            url: admin_url + 'leads_chat/get_messages',
            type: 'POST',
            dataType: 'json',
            data: postData,
            success: function(res) {
                xhrLoadMessages = null;
                if (res.success) {
                    isUserAdmin = res.is_admin;
                    
                    // Render Admin filtering panel
                    if (isUserAdmin) {
                        $('#admin-chat-filter-wrapper').removeClass('hide');
                        updateAdminStaffFilterDropdown(res.staff_members);
                    } else {
                        $('#admin-chat-filter-wrapper').addClass('hide');
                    }

                    renderMessages(res.messages, res.current_user_id, shouldScroll);
                } else {
                    console.error("Failed to load messages: " + res.message);
                }
            },
            error: function(xhr, status, err) {
                if (status !== 'abort') {
                    xhrLoadMessages = null;
                    console.error("Network error reading messages");
                }
            }
        });
    }

    // Populate the Admin Staff selector
    function updateAdminStaffFilterDropdown(staffList) {
        var $dropdown = $('#admin-staff-filter');
        // If options are already loaded beyond the default Unified option, skip rebuilding
        if ($dropdown.find('option').length > 1) return;

        if (staffList && staffList.length > 0) {
            staffList.forEach(function(staff) {
                $dropdown.append(
                    $('<option></option>')
                        .attr('value', staff.staffid)
                        .text(staff.firstname + ' ' + staff.lastname)
                        .css('color', '#333')
                );
            });
        }
    }

    // Render Messages list
    function renderMessages(messages, currentUserId, shouldScroll) {
        var $container = $('#chat-messages-wrapper');
        
        // If it's a fresh load (lastMessageId is 0) and there are no messages
        if (lastMessageId === 0 && messages.length === 0) {
            $container.html('<div class="text-center text-muted" style="margin-top: 50px;"><i class="fa-solid fa-microphone fa-3x" style="opacity: 0.3; margin-bottom: 10px;"></i><p>No audio updates for this lead yet.<br/>Record a voice message below.</p></div>');
            chatLastMessageCount = 0;
            return;
        }

        // If there are no new messages returned, do nothing
        if (messages.length === 0) {
            return;
        }

        // If it's an initial load, empty the placeholder
        if (lastMessageId === 0) {
            $container.empty();
        } else {
            // Remove the empty placeholder if it exists when appending new messages
            $container.find('.text-muted').remove();
        }

        messages.forEach(function(msg) {
            // Prevent duplicate message renders due to race conditions
            if ($container.find('[data-msg-id="' + msg.id + '"]').length > 0) {
                return;
            }

            var isMsgFromMe = (parseInt(msg.sender_id) === parseInt(currentUserId));
            var bubbleClass = isMsgFromMe ? 'chat-message-right' : 'chat-message-left';
            
            // Build bubble container
            var $bubble = $('<div></div>').addClass('chat-message-bubble').addClass(bubbleClass).attr('data-msg-id', msg.id);
            
            // Add sender name and role tags (if not me, or if me is admin showing targeted context)
            var roleTag = '';
            if (msg.sender_role === 'admin') {
                roleTag = '<span class="chat-msg-role-tag tag-admin">Admin</span>';
            } else {
                roleTag = '<span class="chat-msg-role-tag tag-staff">Staff</span>';
            }

            var senderLabel = '';
            if (!isMsgFromMe) {
                senderLabel = $('<div></div>')
                    .addClass('chat-msg-sender')
                    .html(msg.sender_name + roleTag);
                $bubble.append(senderLabel);
            } else if (isUserAdmin) {
                // If Admin sent, show who they sent it to (or global)
                var targetText = '';
                if (parseInt(msg.staff_id) === 0) {
                    targetText = ' (Global)';
                }
                senderLabel = $('<div></div>')
                    .addClass('chat-msg-sender')
                    .html('You' + roleTag + '<span style="font-size:10px; font-weight:normal; opacity:0.85;">' + targetText + '</span>');
                $bubble.append(senderLabel);
            }

            // Build Message Content (Text vs Voice)
            if (msg.message_type === 'text') {
                var $textNode = $('<div></div>').addClass('chat-msg-content').text(msg.message);
                $bubble.append($textNode);
            } else if (msg.message_type === 'voice') {
                var $player = $('<div></div>').addClass('custom-audio-player');
                
                var $playBtn = $('<button></button>')
                    .addClass('audio-play-btn')
                    .html('<i class="fa fa-play"></i>')
                    .attr('data-audio-src', msg.message);
                
                var $progressContainer = $('<div></div>').addClass('audio-progress-container');
                var $progressBar = $('<div></div>').addClass('audio-progress-bar');
                $progressContainer.append($progressBar);

                var $durationText = $('<span></span>').addClass('audio-duration').text('0:00');

                $player.append($playBtn).append($progressContainer).append($durationText);
                $bubble.append($player);
            }

            // Add Time
            var $timeNode = $('<div></div>').addClass('chat-msg-time').text(msg.relative_time);
            $bubble.append($timeNode);

            $container.append($bubble);
        });

        // Set lastMessageId to the ID of the last message in array
        lastMessageId = parseInt(messages[messages.length - 1].id);
        chatLastMessageCount += messages.length;

        // Auto-scroll to the bottom of the container
        if (shouldScroll || lastMessageId > 0) {
            $container.animate({ scrollTop: $container[0].scrollHeight }, 200);
        }
    }

    // Send Text Message
    function sendChatMessage() {
        var messageText = $('#chat-text-input').val().trim();
        if (!chatActiveLeadId || messageText === '') return;

        var filterStaffId = $('#admin-staff-filter').val();

        // Clear input immediately to make it feel super snappy
        $('#chat-text-input').val('');

        var postData = {
            lead_id: chatActiveLeadId,
            message: messageText,
            staff_id: filterStaffId
        };
        if (typeof csrfData !== 'undefined') {
            postData[csrfData.token_name] = csrfData.hash;
        }

        $.ajax({
            url: admin_url + 'leads_chat/send_message',
            type: 'POST',
            dataType: 'json',
            data: postData,
            success: function(res) {
                if (res.success) {
                    loadChatMessages(true);
                } else {
                    alert('Error: ' + res.message);
                }
            },
            error: function() {
                alert('Connection error sending message.');
            }
        });
    }

    // Start polling synchronizer (every 3s)
    function startPolling() {
        stopPolling(); // Ensure clear previous
        pollingIntervalId = setInterval(function() {
            loadChatMessages(false);
        }, 3000);
    }

    // Stop polling
    function stopPolling() {
        if (pollingIntervalId) {
            clearInterval(pollingIntervalId);
            pollingIntervalId = null;
        }
    }

    // Voice Recording implementation
    $('#record-voice-btn').on('click', function() {
        startVoiceRecording();
    });

    $('#cancel-recording-btn').on('click', function() {
        stopVoiceRecording();
    });

    $('#stop-send-recording-btn').on('click', function() {
        sendVoiceRecording();
    });

    function startVoiceRecording() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert('Your browser does not support audio recording.');
            return;
        }

        navigator.mediaDevices.getUserMedia({ audio: true }).then(function(stream) {
            audioChunks = [];
            mediaRecorder = new MediaRecorder(stream);
            
            mediaRecorder.ondataavailable = function(e) {
                audioChunks.push(e.data);
            };

            mediaRecorder.onstop = function() {
                // Done recording
            };

            // Start recording
            mediaRecorder.start();
            recordingStartTime = Date.now();
            
            // UI elements switch
            $('#voice-recording-indicator').removeClass('hide');
            
            // Timer ticker
            clearInterval(recordingTimerInterval);
            recordingTimerInterval = setInterval(function() {
                var seconds = Math.floor((Date.now() - recordingStartTime) / 1000);
                var m = Math.floor(seconds / 60).toString().padStart(2, '0');
                var s = (seconds % 60).toString().padStart(2, '0');
                $('#recording-timer').text(m + ':' + s);
            }, 1000);

        }).catch(function(err) {
            console.error('Audio capture permission denied or failed', err);
            alert('Could not access microphone: ' + err.message);
        });
    }

    function stopVoiceRecording() {
        if (mediaRecorder && mediaRecorder.state !== 'inactive') {
            mediaRecorder.stop();
            // Stop media stream tracks to turn off recording light
            if (mediaRecorder.stream) {
                mediaRecorder.stream.getTracks().forEach(function(track) { track.stop(); });
            }
        }
        
        clearInterval(recordingTimerInterval);
        $('#voice-recording-indicator').addClass('hide');
        $('#recording-timer').text('00:00');
        
        mediaRecorder = null;
        audioChunks = [];
    }

    function sendVoiceRecording() {
        if (!mediaRecorder || mediaRecorder.state === 'inactive') return;

        // Hook onstop to perform the save once recording finishes writing to buffer
        mediaRecorder.onstop = function() {
            var audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
            var formData = new FormData();
            formData.append('audio', audioBlob, 'voice_message.wav');
            formData.append('lead_id', chatActiveLeadId);

            var filterStaffId = $('#admin-staff-filter').val();
            if (isUserAdmin && filterStaffId) {
                formData.append('staff_id', filterStaffId);
            }

            // Append CSRF token manually since FormData skips jQuery's auto CSRF ajaxSetup injection
            if (typeof csrfData !== 'undefined') {
                formData.append(csrfData.token_name, csrfData.hash);
            }

            // Show a sending layout
            $('#chat-messages-wrapper').append(
                $('<div></div>')
                    .addClass('chat-message-bubble chat-message-right voice-sending-temp')
                    .html('<div style="display:flex; align-items:center; gap:8px;"><i class="fa-solid fa-spinner fa-spin"></i> Sending voice message...</div>')
            );
            var $wrapper = $('#chat-messages-wrapper');
            $wrapper.scrollTop($wrapper[0].scrollHeight);

            $.ajax({
                url: admin_url + 'leads_chat/send_voice_message',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(res) {
                    $('.voice-sending-temp').remove();
                    if (res.success) {
                        loadChatMessages(true);
                    } else {
                        alert('Upload failed: ' + res.message);
                    }
                },
                error: function() {
                    $('.voice-sending-temp').remove();
                    alert('Connection error uploading audio.');
                }
            });

            // Stop track elements
            if (mediaRecorder.stream) {
                mediaRecorder.stream.getTracks().forEach(function(track) { track.stop(); });
            }
            mediaRecorder = null;
            audioChunks = [];
        };

        // Stop the recording, triggering onstop hook
        mediaRecorder.stop();
        clearInterval(recordingTimerInterval);
        $('#voice-recording-indicator').addClass('hide');
        $('#recording-timer').text('00:00');
    }

    // Custom Beautiful Audio Player Engine
    $(document).on('click', '.audio-play-btn', function() {
        var $btn = $(this);
        var src = $btn.data('audio-src');
        var $player = $btn.closest('.custom-audio-player');
        var $bar = $player.find('.audio-progress-bar');
        var $duration = $player.find('.audio-duration');

        // If clicking a currently playing audio play button, toggle it
        if (activeAudio && activePlayButton && activePlayButton[0] === $btn[0]) {
            if (activeAudio.paused) {
                activeAudio.play();
                $btn.html('<i class="fa fa-pause"></i>');
            } else {
                activeAudio.pause();
                $btn.html('<i class="fa fa-play"></i>');
            }
            return;
        }

        // Stop previous audio first
        stopCurrentlyPlayingAudio();

        // Create new Audio instance
        activeAudio = new Audio(src);
        activePlayButton = $btn;
        activeProgressBar = $bar;

        $btn.html('<i class="fa-solid fa-spinner fa-spin"></i>');

        activeAudio.addEventListener('canplaythrough', function() {
            $btn.html('<i class="fa fa-pause"></i>');
            activeAudio.play();
        });

        activeAudio.addEventListener('timeupdate', function() {
            if (!activeAudio) return;
            var cur = activeAudio.currentTime;
            var dur = activeAudio.duration || 0;
            var pct = dur > 0 ? (cur / dur) * 100 : 0;
            
            $bar.css('width', pct + '%');

            // Render duration
            var m = Math.floor(cur / 60);
            var s = Math.floor(cur % 60).toString().padStart(2, '0');
            $duration.text(m + ':' + s);
        });

        activeAudio.addEventListener('ended', function() {
            $btn.html('<i class="fa fa-play"></i>');
            $bar.css('width', '0%');
            $duration.text('0:00');
            activeAudio = null;
            activePlayButton = null;
            activeProgressBar = null;
        });

        activeAudio.addEventListener('error', function() {
            $btn.html('<i class="fa fa-exclamation-triangle text-danger"></i>');
            alert('Error loading voice message audio file.');
            activeAudio = null;
            activePlayButton = null;
            activeProgressBar = null;
        });

        activeAudio.load();
    });

    function stopCurrentlyPlayingAudio() {
        if (activeAudio) {
            activeAudio.pause();
            if (activePlayButton) {
                activePlayButton.html('<i class="fa fa-play"></i>');
            }
            if (activeProgressBar) {
                activeProgressBar.css('width', '0%');
            }
            activeAudio = null;
            activePlayButton = null;
            activeProgressBar = null;
        }
    }
});
</script>
