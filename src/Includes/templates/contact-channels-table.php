<?php

if (!defined('ABSPATH')) {
    exit;
}

use WP28\PAGUECOMPIX\Includes\Core\Config;

?>

<tr>
    <th scope="row" class="titledesc"><?php esc_html_e('Contact channels to share receipt payments:', Config::getTextDomain()); ?></th>
    <td class="forminp" id="wp28_contact_channels">
        <div class="wc_input_table_wrapper">
            <table class="widefat wc_input_table sortable">
                <thead>
                    <tr>
                        <th class="sort">&nbsp;</th>
                        <th><?php esc_html_e('Channel (e.g.: E-mail, Whatsapp, etc...) ', Config::getTextDomain()); ?></th>
                        <th><?php esc_html_e('Value', Config::getTextDomain()); ?></th>
                    </tr>
                </thead>
                <tbody class="channels">
                    <?php
                    $i = -1;
                    if ($this->contact_channels) {
                        foreach ($this->contact_channels as $channel) {
                            $i++;

                            echo '<tr class="channel">
										<td class="sort"></td>
										<td>
										    <select name="pix_contact_name[' . esc_attr($i) . ']" class="pix_contact_name" style="width: 100%;">
                                                <option value="whatsapp" ' . esc_attr("whatsapp" === esc_attr($channel['contact_name']) ? "selected" : "") . '>Whatsapp</option>
                                                <option value="telegram" ' . esc_attr("telegram" === esc_attr($channel['contact_name']) ? "selected" : "") . '>Telegram</option>
                                                <option value="email" ' . esc_attr("email" === esc_attr($channel['contact_name']) ? "selected" : "")  . '>E-mail</option>
                                                <option value="outro" ' . esc_attr("outro" === esc_attr($channel['contact_name']) ? "selected" : "") . '>Outro</option>
                                            </select>
										</td>
										<td><input type="text" value="' . esc_attr($channel['contact_value']) . '" name="pix_contact_value[' . esc_attr($i) . ']" /></td>
									</tr>';
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">
                            <a href="#" class="add button"><?php esc_html_e('+ Add Channel', Config::getTextDomain()); ?></a>
                            <a href="#" class="remove_rows button"><?php esc_html_e('Remove selected channel(s)', Config::getTextDomain()); ?></a>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </td>
</tr>