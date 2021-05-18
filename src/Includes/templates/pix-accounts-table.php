<?php

if (!defined('ABSPATH')) {
    exit;
}

use WP28\PAGUECOMPIX\Includes\Core\Config;

?>

<tr>
    <th scope="row" class="titledesc"><?php esc_html_e('Register Pix Keys:', Config::getTextDomain()); ?></th>
    <td class="forminp" id="wp28_pix_accounts">
        <div class="wc_input_table_wrapper">
            <table class="widefat wc_input_table sortable">
                <thead>
                    <tr>
                        <th class="sort">&nbsp;</th>
                        <th><?php esc_html_e('Bank Name', Config::getTextDomain()); ?></th>
                        <th><?php esc_html_e('Key Type', Config::getTextDomain()); ?></th>
                        <th><?php esc_html_e('Pix Key', Config::getTextDomain()); ?></th>
                        <th><?php esc_html_e('Holder Name', Config::getTextDomain()); ?></th>
                        <th><?php esc_html_e('Holder City', Config::getTextDomain()); ?></th>
                    </tr>
                </thead>
                <tbody class="accounts">
                    <?php
                    $i = -1;
                    if ($this->accounts_list) {
                        foreach ($this->accounts_list as $account) {
                            $i++;
                            echo '<tr class="account">
										<td class="sort"></td>
										<td><input type="text" value="' . esc_attr(wp_unslash($account['bank'])) . '" name="pix_bank[' . esc_attr($i) . ']" /></td>
                                        <td>
                                            <select name="pix_key_type[' . esc_attr($i) . ']" class="pix_key_type" style="width: 100%;">
                                                <option value="cpf" ' . esc_attr("cpf" === esc_attr($account['key_type']) ? "selected" : "") . '>CPF</option>
                                                <option value="cnpj" ' . esc_attr("cnpj" === esc_attr($account['key_type']) ? "selected" : "") . '>CNPJ</option>
                                                <option value="email" ' . esc_attr("email" === esc_attr($account['key_type']) ? "selected" : "")  . '>E-mail</option>
                                                <option value="telefone" ' . esc_attr("telefone" === esc_attr($account['key_type']) ? "selected" : "") . '>Telefone</option>
                                                <option value="evp" ' . esc_attr("evp" === esc_attr($account['key_type']) ? "selected" : "") . '>Aleatória</option>
                                            </select>
                                        </td>
										<td><input type="text" value="' . esc_attr($account['key_code']) . '" name="pix_key_code[' . esc_attr($i) . ']" /></td>
										<td><input type="text" value="' . esc_attr(wp_unslash($account['holder_name'])) . '" name="pix_holder_name[' . esc_attr($i) . ']" /></td>
										<td><input type="text" value="' . esc_attr(wp_unslash($account['holder_city'])) . '" name="pix_holder_city[' . esc_attr($i) . ']" /></td>
									</tr>';
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6">
                            <a href="#" class="add button"><?php esc_html_e('+ Add Key', Config::getTextDomain()); ?></a>
                            <a href="#" class="remove_rows button"><?php esc_html_e('Remove selected key(s)', Config::getTextDomain()); ?></a>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </td>
</tr>