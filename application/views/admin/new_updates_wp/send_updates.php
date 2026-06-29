<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h4 class="tw-mt-0 tw-font-bold tw-text-xl tw-text-neutral-700 tw-flex tw-items-center">
                    <i class="fa-brands fa-whatsapp tw-text-green-500 tw-mr-2 tw-text-2xl"></i>
                    <?= e($title); ?>
                </h4>
                
                <style>
                    .wp-panel {
                        border-radius: 12px;
                        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
                        border: 1px solid #e2e8f0;
                        background: #ffffff;
                        margin-bottom: 24px;
                    }
                    .wp-header {
                        background: linear-gradient(135deg, #128C7E 0%, #075E54 100%);
                        color: #ffffff;
                        padding: 18px 24px;
                        border-top-left-radius: 12px;
                        border-top-right-radius: 12px;
                        font-weight: 600;
                        font-size: 16px;
                    }
                    .wp-body {
                        padding: 24px;
                    }
                    .recipient-box {
                        border: 1px solid #cbd5e1;
                        border-radius: 8px;
                        max-height: 350px;
                        overflow-y: auto;
                        padding: 12px;
                        background: #f8fafc;
                    }
                    .recipient-item {
                        padding: 8px 12px;
                        margin-bottom: 6px;
                        border-radius: 6px;
                        background: #ffffff;
                        border: 1px solid #e2e8f0;
                        transition: all 0.2s ease;
                        display: flex;
                        align-items: center;
                    }
                    .recipient-item:hover {
                        border-color: #128C7E;
                        background: #f0fdf4;
                    }
                    .recipient-item input[type="checkbox"] {
                        margin-right: 12px;
                        width: 16px;
                        height: 16px;
                        accent-color: #128C7E;
                        cursor: pointer;
                    }
                    .nav-tabs-custom {
                        margin-bottom: 15px;
                        border-bottom: 2px solid #f1f5f9;
                    }
                    .nav-tabs-custom > li > a {
                        border: none;
                        background: transparent;
                        color: #64748b;
                        font-weight: 500;
                        padding: 10px 16px;
                        transition: all 0.2s ease;
                    }
                    .nav-tabs-custom > li.active > a,
                    .nav-tabs-custom > li > a:hover {
                        border: none;
                        background: transparent;
                        color: #128C7E;
                        border-bottom: 3px solid #128C7E;
                    }
                    .search-input {
                        border-radius: 20px;
                        padding-left: 15px;
                        border: 1px solid #cbd5e1;
                        margin-bottom: 12px;
                    }
                    .file-dropzone {
                        border: 2px dashed #cbd5e1;
                        border-radius: 8px;
                        padding: 24px;
                        text-align: center;
                        background: #f8fafc;
                        cursor: pointer;
                        transition: all 0.2s ease;
                    }
                    .file-dropzone:hover {
                        border-color: #128C7E;
                        background: #f0fdf4;
                    }
                    .file-dropzone i {
                        font-size: 32px;
                        color: #64748b;
                        margin-bottom: 8px;
                    }
                    .file-dropzone.has-file {
                        border-color: #128C7E;
                        background: #ecfdf5;
                    }
                    .file-dropzone.has-file i {
                        color: #10b981;
                    }
                    .btn-wp-send {
                        background: #128C7E;
                        color: white;
                        border: none;
                        border-radius: 8px;
                        padding: 12px 24px;
                        font-weight: 600;
                        font-size: 16px;
                        transition: all 0.3s ease;
                        box-shadow: 0 4px 12px rgba(18, 140, 126, 0.2);
                    }
                    .btn-wp-send:hover {
                        background: #075E54;
                        color: white;
                        box-shadow: 0 6px 16px rgba(7, 94, 84, 0.3);
                        transform: translateY(-1px);
                    }
                    .badge-count {
                        background: #e2e8f0;
                        color: #475569;
                        border-radius: 12px;
                        padding: 2px 8px;
                        font-size: 12px;
                        margin-left: 6px;
                        font-weight: 600;
                    }
                    .badge-count.selected {
                        background: #128C7E;
                        color: white;
                    }
                    /* Progress overlay */
                    .progress-overlay {
                        position: fixed;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        background: rgba(15, 23, 42, 0.6);
                        backdrop-filter: blur(4px);
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        z-index: 9999;
                        opacity: 0;
                        pointer-events: none;
                        transition: opacity 0.3s ease;
                    }
                    .progress-overlay.active {
                        opacity: 1;
                        pointer-events: auto;
                    }
                    .progress-card {
                        background: white;
                        border-radius: 16px;
                        width: 500px;
                        max-width: 90%;
                        padding: 30px;
                        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                    }
                </style>

                <div class="row">
                    <!-- Progress Overlay Modal -->
                    <div class="progress-overlay" id="send_progress_overlay">
                        <div class="progress-card">
                            <h4 class="tw-font-bold tw-text-neutral-800 tw-mt-0 tw-mb-2 tw-flex tw-items-center">
                                <i class="fa-brands fa-whatsapp tw-text-green-500 tw-mr-2"></i> Sending WhatsApp Campaign
                            </h4>
                            <p class="text-muted tw-text-sm" id="progress_status_label">Preparing attachment upload...</p>
                            
                            <!-- Progress Bar -->
                            <div class="progress tw-mb-4" style="height: 10px; border-radius: 5px;">
                                <div class="progress-bar progress-bar-success" id="progress_bar" role="progressbar" style="width: 0%; border-radius: 5px;"></div>
                            </div>
                            
                            <!-- Counter details -->
                            <div class="tw-flex tw-justify-between tw-text-sm tw-font-semibold tw-text-neutral-700 tw-mb-4">
                                <span id="progress_counter">0 / 0 Sent</span>
                                <span id="progress_percent">0%</span>
                            </div>

                            <!-- Live logs -->
                            <div id="progress_logs" style="max-height: 200px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; background: #f8fafc; font-family: monospace; font-size: 11px;">
                                <div class="text-muted">Campaign initialized...</div>
                            </div>

                            <div class="tw-mt-6 tw-text-right" id="progress_actions">
                                <button type="button" class="btn btn-default btn-sm" id="abort_btn" onclick="abortSending()">Stop Sending</button>
                                <button type="button" class="btn btn-primary btn-sm" id="close_progress_btn" style="display: none;" onclick="closeProgressModal()">Close Dashboard</button>
                            </div>
                        </div>
                    </div>

                    <?= form_open_multipart(admin_url('new_updates_wp'), ['id' => 'wp_send_form']); ?>
                    
                    <!-- Left Column: Message details -->
                    <div class="col-md-6">
                        <div class="wp-panel">
                            <div class="wp-header">
                                <i class="fa-regular fa-paper-plane tw-mr-2"></i> Compose Update
                            </div>
                            <div class="wp-body">
                                <div class="form-group">
                                    <label for="message" class="control-label tw-font-semibold tw-text-neutral-700">Message Text</label>
                                    <textarea name="message" id="message" class="form-control" rows="6" placeholder="Type your WhatsApp update message here..." required></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="control-label tw-font-semibold tw-text-neutral-700">Attachment (Image, Video, Audio - Max 30MB)</label>
                                    <div class="file-dropzone" id="dropzone_trigger" onclick="document.getElementById('attachment').click()">
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                        <h5 class="tw-font-semibold tw-mb-1" id="file_status_title">Drag & Drop or Click to Upload</h5>
                                        <p class="text-muted tw-text-xs tw-mb-0">Supports images, video, and audio files up to 30MB</p>
                                        <input type="file" name="attachment" id="attachment" style="display: none;" accept="image/*,video/*,audio/*">
                                    </div>
                                    <div class="tw-mt-2 tw-flex tw-justify-between tw-items-center">
                                        <span id="selected_file_name" class="tw-text-sm tw-text-neutral-600 tw-font-medium"></span>
                                        <button type="button" id="clear_file_btn" class="btn btn-link btn-xs text-danger" style="display: none;">Remove File</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Recipient selection -->
                    <div class="col-md-6">
                        <div class="wp-panel">
                            <div class="wp-header tw-flex tw-justify-between tw-items-center">
                                <span><i class="fa-solid fa-users tw-mr-2"></i> Select Recipients</span>
                                <span class="badge-count selected" id="total_selected_badge">0 Selected</span>
                            </div>
                            <div class="wp-body">
                                <!-- Tabs for Staff / Leads / Recent Send -->
                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                    <li role="presentation" class="active">
                                        <a href="#tab_staff" aria-controls="tab_staff" role="tab" data-toggle="tab">
                                            Staff <span class="badge-count" id="staff_count"><?= count($staff); ?></span>
                                        </a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#tab_leads" aria-controls="tab_leads" role="tab" data-toggle="tab">
                                            Leads <span class="badge-count" id="leads_count"><?= count($leads); ?></span>
                                        </a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#tab_history" aria-controls="tab_history" role="tab" data-toggle="tab">
                                            Recent Send <span class="badge-count" id="history_count"><?= count($history); ?></span>
                                        </a>
                                    </li>
                                </ul>

                                <!-- Tab Content -->
                                <div class="tab-content">
                                    <!-- Staff Tab -->
                                    <div role="tabpanel" class="tab-pane active" id="tab_staff">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <input type="text" id="search_staff" class="form-control search-input" placeholder="Search staff...">
                                            </div>
                                            <div class="col-md-4 tw-text-right">
                                                <button type="button" class="btn btn-default btn-xs" onclick="toggleSelectAll('staff')">Toggle All</button>
                                            </div>
                                        </div>
                                        <div class="recipient-box" id="staff_list">
                                            <?php foreach ($staff as $s): ?>
                                                <?php if (!empty($s['phonenumber'])): ?>
                                                    <div class="recipient-item" data-search="<?= strtolower(e($s['firstname'] . ' ' . $s['lastname'] . ' ' . $s['email'] . ' ' . $s['phonenumber'])); ?>">
                                                        <input type="checkbox" name="staff[]" value="<?= $s['staffid']; ?>" data-phone="<?= e($s['phonenumber']); ?>" data-name="<?= e($s['firstname'] . ' ' . $s['lastname']); ?> (Staff)" class="staff-checkbox" onchange="updateSelectedCount()">
                                                        <div>
                                                            <div class="tw-font-semibold tw-text-neutral-700"><?= e($s['firstname'] . ' ' . $s['lastname']); ?></div>
                                                            <div class="text-muted tw-text-xs"><?= e($s['email']); ?> &bull; <?= e($s['phonenumber']); ?></div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                    <!-- Leads Tab -->
                                    <div role="tabpanel" class="tab-pane" id="tab_leads">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <input type="text" id="search_leads" class="form-control search-input" placeholder="Search leads...">
                                            </div>
                                            <div class="col-md-4 tw-text-right">
                                                <button type="button" class="btn btn-default btn-xs" onclick="toggleSelectAll('leads')">Toggle All</button>
                                            </div>
                                        </div>
                                        <div class="recipient-box" id="leads_list">
                                            <?php foreach ($leads as $lead): ?>
                                                <?php if (!empty($lead['phonenumber'])): ?>
                                                    <div class="recipient-item" data-search="<?= strtolower(e($lead['name'] . ' ' . $lead['company'] . ' ' . $lead['phonenumber'])); ?>">
                                                        <input type="checkbox" name="leads[]" value="<?= $lead['id']; ?>" data-phone="<?= e($lead['phonenumber']); ?>" data-name="<?= e($lead['name']); ?> (Lead)" class="lead-checkbox" onchange="updateSelectedCount()">
                                                        <div>
                                                            <div class="tw-font-semibold tw-text-neutral-700"><?= e($lead['name']); ?></div>
                                                            <div class="text-muted tw-text-xs"><?= e($lead['company'] ?: 'No Company'); ?> &bull; <?= e($lead['phonenumber']); ?></div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                    <!-- Recent Send Tab -->
                                    <div role="tabpanel" class="tab-pane" id="tab_history">
                                        <div style="max-height: 380px; overflow-y: auto;">
                                            <table class="table table-bordered table-striped tw-text-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Date Sent</th>
                                                        <th>Message</th>
                                                        <th>Attachment</th>
                                                        <th>Sent Summary</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (empty($history)): ?>
                                                        <tr>
                                                            <td colspan="4" class="text-center text-muted">No recent broadcast history.</td>
                                                        </tr>
                                                    <?php else: ?>
                                                        <?php foreach ($history as $item): ?>
                                                            <tr>
                                                                <td><?= e($item['date_sent']); ?></td>
                                                                <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?= e($item['message']); ?>"><?= e($item['message']); ?></td>
                                                                <td><?= e($item['attachment_name'] ?: 'None'); ?></td>
                                                                <td>
                                                                    <span class="text-success"><?= $item['success_count']; ?> Ok</span> / 
                                                                    <span class="text-danger"><?= $item['failed_count']; ?> Fail</span> 
                                                                    (<?= $item['recipients_count']; ?> Total)
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="tw-mt-8 tw-text-right">
                                    <button type="button" class="btn btn-wp-send" id="submit_btn" onclick="startCampaign()">
                                        <i class="fa-brands fa-whatsapp tw-mr-1"></i> Send WhatsApp Update
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<script>
    // Search filter for Staff
    document.getElementById('search_staff').addEventListener('input', function(e) {
        var query = e.target.value.toLowerCase();
        var items = document.querySelectorAll('#staff_list .recipient-item');
        items.forEach(function(item) {
            var searchData = item.getAttribute('data-search');
            if (searchData.indexOf(query) > -1) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Search filter for Leads
    document.getElementById('search_leads').addEventListener('input', function(e) {
        var query = e.target.value.toLowerCase();
        var items = document.querySelectorAll('#leads_list .recipient-item');
        items.forEach(function(item) {
            var searchData = item.getAttribute('data-search');
            if (searchData.indexOf(query) > -1) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Toggle All checkboxes for a category
    function toggleSelectAll(type) {
        var checkboxes = document.querySelectorAll('.' + (type === 'staff' ? 'staff' : 'lead') + '-checkbox');
        var allChecked = true;
        
        // Check if all visible checkboxes are checked
        var visibleCheckboxes = Array.from(checkboxes).filter(cb => cb.closest('.recipient-item').style.display !== 'none');
        if (visibleCheckboxes.length > 0) {
            var checkedCount = visibleCheckboxes.filter(cb => cb.checked).length;
            allChecked = checkedCount === visibleCheckboxes.length;
        }

        visibleCheckboxes.forEach(function(cb) {
            cb.checked = !allChecked;
        });

        updateSelectedCount();
    }

    // Update the counter showing selected items
    function updateSelectedCount() {
        var selectedStaff = document.querySelectorAll('.staff-checkbox:checked').length;
        var selectedLeads = document.querySelectorAll('.lead-checkbox:checked').length;
        var total = selectedStaff + selectedLeads;
        
        document.getElementById('total_selected_badge').innerText = total + ' Selected';
    }

    // File input selection event
    var fileInput = document.getElementById('attachment');
    var fileDropzone = document.getElementById('dropzone_trigger');
    
    fileInput.addEventListener('change', function(e) {
        if (fileInput.files.length > 0) {
            var file = fileInput.files[0];
            
            // Check size limit: 30MB
            if (file.size > 30 * 1024 * 1024) {
                alert_float('danger', 'File size exceeds the 30MB limit.');
                fileInput.value = '';
                clearFileDisplay();
                return;
            }
            
            fileDropzone.classList.add('has-file');
            document.getElementById('file_status_title').innerText = 'File selected successfully!';
            document.getElementById('selected_file_name').innerText = file.name + ' (' + (file.size / (1024 * 1024)).toFixed(2) + ' MB)';
            document.getElementById('clear_file_btn').style.display = 'inline-block';
        }
    });

    document.getElementById('clear_file_btn').addEventListener('click', function() {
        fileInput.value = '';
        clearFileDisplay();
    });

    function clearFileDisplay() {
        fileDropzone.classList.remove('has-file');
        document.getElementById('file_status_title').innerText = 'Drag & Drop or Click to Upload';
        document.getElementById('selected_file_name').innerText = '';
        document.getElementById('clear_file_btn').style.display = 'none';
    }

    // AJAX Bulk Campaign variables
    var activeCampaign = false;
    var abortSignal = false;

    function abortSending() {
        abortSignal = true;
        addLog("Campaign aborted by user.");
        document.getElementById('abort_btn').style.display = 'none';
        document.getElementById('close_progress_btn').style.display = 'inline-block';
        document.getElementById('progress_status_label').innerText = 'Campaign stopped.';
    }

    function addLog(text, type = 'info') {
        var logBox = document.getElementById('progress_logs');
        var color = 'text-neutral-600';
        if (type === 'success') color = 'text-success';
        if (type === 'danger') color = 'text-danger';
        logBox.innerHTML += '<div class="' + color + '">[' + new Date().toLocaleTimeString() + '] ' + text + '</div>';
        logBox.scrollTop = logBox.scrollHeight;
    }

    function closeProgressModal() {
        document.getElementById('send_progress_overlay').classList.remove('active');
        window.location.reload(); // Refresh to update history tab
    }

    function startCampaign() {
        // Validate inputs
        var message = document.getElementById('message').value.trim();
        if (message === '') {
            alert_float('warning', 'Please enter a message text.');
            return;
        }

        var selectedStaffElements = document.querySelectorAll('.staff-checkbox:checked');
        var selectedLeadsElements = document.querySelectorAll('.lead-checkbox:checked');
        var totalRecipients = selectedStaffElements.length + selectedLeadsElements.length;

        if (totalRecipients === 0) {
            alert_float('warning', 'Please select at least one staff member or lead.');
            return;
        }

        // Build recipient queue
        var queue = [];
        selectedStaffElements.forEach(cb => {
            queue.push({
                phone: cb.getAttribute('data-phone'),
                name: cb.getAttribute('data-name')
            });
        });
        selectedLeadsElements.forEach(cb => {
            queue.push({
                phone: cb.getAttribute('data-phone'),
                name: cb.getAttribute('data-name')
            });
        });

        // Initialize overlay
        document.getElementById('send_progress_overlay').classList.add('active');
        document.getElementById('abort_btn').style.display = 'inline-block';
        document.getElementById('close_progress_btn').style.display = 'none';
        document.getElementById('progress_logs').innerHTML = '';
        
        activeCampaign = true;
        abortSignal = false;

        var successCount = 0;
        var failedCount = 0;

        // Check if there is an attachment
        if (fileInput.files.length > 0) {
            addLog("Uploading attachment to server...");
            document.getElementById('progress_status_label').innerText = 'Uploading attachment...';
            
            var formData = new FormData();
            formData.append('attachment', fileInput.files[0]);
            formData.append(csrfData.token_name, csrfData.hash);

            $.ajax({
                url: admin_url + 'new_updates_wp/upload_attachment',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        addLog("Attachment uploaded successfully. Starting broadcast...");
                        processQueue(queue, message, res.temp_file, res.original_name, 0, successCount, failedCount);
                    } else {
                        addLog("Attachment upload failed: " + res.error, 'danger');
                        campaignFinished(message, fileInput.files[0].name, totalRecipients, 0, totalRecipients, null);
                    }
                },
                error: function() {
                    addLog("Network error uploading file.", 'danger');
                    campaignFinished(message, fileInput.files[0].name, totalRecipients, 0, totalRecipients, null);
                }
            });
        } else {
            addLog("No attachment. Starting text broadcast...");
            processQueue(queue, message, null, null, 0, successCount, failedCount);
        }
    }

    // Process sending queue one-by-one to avoid overload and allow live updates
    function processQueue(queue, message, tempFile, originalName, index, successCount, failedCount) {
        if (abortSignal) {
            campaignFinished(message, originalName, queue.length, successCount, failedCount, tempFile);
            return;
        }

        if (index >= queue.length) {
            addLog("All messages processed.", 'success');
            campaignFinished(message, originalName, queue.length, successCount, failedCount, tempFile);
            return;
        }

        var recipient = queue[index];
        document.getElementById('progress_status_label').innerText = 'Sending to ' + recipient.name + '...';
        
        var percent = Math.round((index / queue.length) * 100);
        document.getElementById('progress_bar').style.width = percent + '%';
        document.getElementById('progress_percent').innerText = percent + '%';
        document.getElementById('progress_counter').innerText = index + ' / ' + queue.length + ' Sent';

        var postData = {
            phone: recipient.phone,
            name: recipient.name,
            message: message,
            temp_file: tempFile,
            original_name: originalName
        };
        postData[csrfData.token_name] = csrfData.hash;

        $.ajax({
            url: admin_url + 'new_updates_wp/send_single',
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    successCount++;
                    addLog("Sent to " + recipient.name, 'success');
                } else {
                    failedCount++;
                    addLog("Failed for " + recipient.name + ": " + res.error, 'danger');
                }
                // Process next item
                setTimeout(function() {
                    processQueue(queue, message, tempFile, originalName, index + 1, successCount, failedCount);
                }, 300); // 300ms delay to avoid overloading gateway
            },
            error: function() {
                failedCount++;
                addLog("Connection timeout / network error for " + recipient.name, 'danger');
                setTimeout(function() {
                    processQueue(queue, message, tempFile, originalName, index + 1, successCount, failedCount);
                }, 500);
            }
        });
    }

    function campaignFinished(message, attachmentName, total, success, failed, tempFile) {
        document.getElementById('progress_bar').style.width = '100%';
        document.getElementById('progress_percent').innerText = '100%';
        document.getElementById('progress_counter').innerText = total + ' / ' + total + ' Sent';
        document.getElementById('progress_status_label').innerText = 'Broadcast completed!';
        
        document.getElementById('abort_btn').style.display = 'none';
        document.getElementById('close_progress_btn').style.display = 'inline-block';

        addLog("Logging campaign history...", 'info');

        var postData = {
            message: message,
            attachment_name: attachmentName,
            recipients_count: total,
            success_count: success,
            failed_count: failed,
            temp_file: tempFile
        };
        postData[csrfData.token_name] = csrfData.hash;

        $.post(admin_url + 'new_updates_wp/log_history', postData, function() {
            addLog("Campaign logged successfully.", 'success');
        });
    }
</script>
