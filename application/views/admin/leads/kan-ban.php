<?php defined('BASEPATH') or exit('No direct script access allowed');
$is_admin = is_admin();
$i        = 0;
foreach ($statuses as $status) {
    $kanBan = new \app\services\leads\LeadsKanban($status['id']);
    $kanBan->search($this->input->get('search'))
    ->sortBy($this->input->get('sort_by'), $this->input->get('sort'));
    if ($this->input->get('refresh')) {
        $kanBan->refresh($this->input->get('refresh')[$status['id']] ?? null);
    }
    $leads       = $kanBan->get();
    $total_leads = count($leads);
    $total_pages = $kanBan->totalPages();

    $settings = '';
    foreach (get_system_favourite_colors() as $color) {
        $color_selected_class = 'cpicker-small';
        if ($color == $status['color']) {
            $color_selected_class = 'cpicker-big';
        }
        $settings .= "<div class='kanban-cpicker cpicker " . $color_selected_class . "' data-color='" . $color . "' style='background:" . $color . ';border:1px solid ' . $color . "'></div>";
    } ?>
<ul class="kan-ban-col" data-col-status-id="<?php echo e($status['id']); ?>" data-total-pages="<?php echo e($total_pages); ?>"
    data-total="<?php echo e($total_leads); ?>">
    <li class="kan-ban-col-wrapper">
        <div class="border-right panel_s">
            <?php
            // Extract the color
            $color = $status['color'];
            if (empty($color)) {
                $color = '#475569'; // Fallback slate
            }

            // Map status names to specific icons matching the mockup
            $status_name_lower = strtolower($status['name']);
            $icon = '<i class="fa-solid fa-circle-dot"></i>'; // fallback icon
            if (strpos($status_name_lower, 'new') !== false || $status['isdefault'] == 1) {
                $icon = '<i class="fa-solid fa-circle-plus"></i>';
            } else if (strpos($status_name_lower, 'qualif') !== false || strpos($status_name_lower, 'customer') !== false) {
                $icon = '<i class="fa-solid fa-circle-check"></i>';
            } else if (strpos($status_name_lower, 'proposal') !== false || strpos($status_name_lower, 'sent') !== false) {
                $icon = '<i class="fa-solid fa-file-invoice"></i>';
            } else if (strpos($status_name_lower, 'negotiat') !== false || strpos($status_name_lower, 'contact') !== false) {
                $icon = '<i class="fa-solid fa-handshake"></i>';
            } else if (strpos($status_name_lower, 'won') !== false || strpos($status_name_lower, 'interested') !== false) {
                $icon = '<i class="fa-solid fa-trophy"></i>';
            }
            ?>
            <div class="panel-heading"
                <?php if ($status['isdefault'] == 1) { ?>data-toggle="tooltip"
                data-title="<?php echo _l('leads_converted_to_client') . ' - ' . _l('client'); ?>" <?php } ?>
                data-status-id="<?php echo e($status['id']); ?>">
                <div class="kanban-header-left">
                    <i class="fa fa-reorder pointer"></i>
                    <span class="kanban-status-pill pointer"
                        style="background-color: <?php echo $color; ?>; border: 1px solid <?php echo $color; ?>; color: #ffffff;"
                        <?php if ($is_admin) { ?>
                        data-order="<?php echo e($status['statusorder']); ?>" data-color="<?php echo e($status['color']); ?>"
                        data-name="<?php echo e($status['name']); ?>"
                        onclick="edit_status(this,<?php echo e($status['id']); ?>); return false;"
                        <?php } ?>>
                        <?php echo $icon; ?>
                        <span class="status-name"><?php echo e($status['name']); ?></span>
                        <span class="status-count"><?php echo e($total_leads); ?></span>
                    </span>
                </div>
                <div class="kanban-header-right">
                    <span class="kanban-status-value">
                        <?php echo app_format_money(
                            $summary[$statusSummaryIndex = array_search($status['id'], array_column($summary, 'id'))]['value'],
                            $base_currency
                        ); ?>
                    </span>
                    <a href="#" onclick="return false;" class="kanban-color-picker kanban-stage-color-picker<?php if ($status['isdefault'] == 1) {
                        echo ' kanban-stage-color-picker-last';
                    } ?>" data-placement="bottom" data-toggle="popover" data-content="
                        <div class='text-center'>
                          <button type='button' return false;' class='btn btn-primary btn-block mtop10 new-lead-from-status'>
                            <?php echo _l('new_lead'); ?>
                          </button>
                        </div>
                        <?php if (is_admin()) {?>
                        <hr />
                        <div class='kan-ban-settings cpicker-wrapper'>
                          <?php echo $settings; ?>
                        </div><?php } ?>" data-html="true" data-trigger="focus">
                        <i class="fa fa-angle-down"></i>
                    </a>
                </div>
            </div>
            <div class="kan-ban-content-wrapper">
                <div class="kan-ban-content">
                    <ul class="status leads-status sortable" data-lead-status-id="<?php echo e($status['id']); ?>" data-visible-count="4">
                        <?php
                        $card_index = 0;
                        foreach ($leads as $lead) {
                            $card_class = '';
                            $card_style = '';
                            if ($card_index >= 4) {
                                $card_class = ' hidden-lead-card';
                                $card_style = 'style="display: none;"';
                            }
                            $this->load->view('admin/leads/_kan_ban_card', [
                                'lead' => $lead, 
                                'status' => $status, 
                                'base_currency' => $base_currency,
                                'card_class' => $card_class,
                                'card_style' => $card_style
                            ]);
                            $card_index++;
                        } ?>
                        <?php if ($total_leads > 4) { ?>
                        <li class="text-center not-sortable custom-kanban-load-more"
                            data-load-status="<?php echo e($status['id']); ?>">
                            <a href="#" class="btn btn-default btn-block"
                                onclick="load_more_local_leads(<?php echo e($status['id']); ?>, this); return false;">
                                <?php echo _l('load_more'); ?>
                            </a>
                        </li>
                        <?php } ?>
                        <li class="text-center not-sortable mtop30 kanban-empty<?php if ($total_leads > 0) {
                            echo ' hide';
                        } ?>">
                            <h4>
                                <i class="fa-solid fa-circle-notch" aria-hidden="true"></i><br /><br />
                                <?php echo _l('no_leads_found'); ?>
                            </h4>
                        </li>
                    </ul>
                </div>
            </div>
    </li>
</ul>
<?php $i++;
} ?>
