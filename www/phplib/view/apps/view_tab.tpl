              <table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
                <tr>
                  <th width="120" scope="col">機器種別</th>
                  <td width="120" scope="col"><?php echo $mgr->getOutputData('app_type_name') ?></td>
                  <th width="100" scope="col">名称</th>
                  <td scope="col"><?php echo $mgr->getOutputData('app_name') ?></td>
                </tr>
<?php if ($mgr->is_wire_free || $mgr->is_ip_free) { ?>
                <tr>
<?php   if ($mgr->is_wire_free) { ?>
                  <th scope="col">有線／無線</th>
<?php     if ($mgr->is_ip_free) { ?>
                  <td scope="col">
<?php     } else { ?>
                  <td scope="col" colspan="3">
<?php     } ?>
                    <?php echo $mgr->getOutputData('wire_kbn_name') ?>
                  </td>
<?php   } ?>
<?php   if ($mgr->is_ip_free) { ?>
                  <th scope="col">IPアドレス</th>
<?php     if ($mgr->is_wire_free) { ?>
                  <td scope="col">
<?php     } else { ?>
                  <td scope="col" colspan="3">
<?php     } ?>
                    <?php echo $mgr->getOutputData('ip_kbn_name') ?>
                  </td>
<?php   } ?>
                </tr>
<?php } ?>
                <tr>
                  <th width="120">設置場所</th>
                  <td colspan="3"><?php echo $mgr->getOutputData('vlan_name') ?></td>
                </tr>
                <tr>
                  <th width="120">MACアドレス</th>
                  <td colspan="3"><?php echo $mgr->getOutputData('mac_addr') ?></td>
                </tr>
<?php if ($mgr->is_agree_mode && $mgr->ip_kbn == IP_KBN_FIXD) { ?>
                <tr>
                  <th width="120">固定IPアドレス<span class="hissu">*</span></th>
                  <td colspan="3"><input name="ip_addr" type="text" id="ip_addr" size="30" maxlength="15" value="<?php echo $mgr->getRequestData('ip_addr') ?>" /></td>
                </tr>
<?php } ?>
                <tr>
                  <th width="120">備考</th>
                  <td colspan="3"><?php echo nl2br($mgr->getOutputData('note')) ?></td>
                </tr>
                <tr>
                  <th width="120">機器利用者</th>
                  <td colspan="3">
                    <?php echo $mgr->getUserName($mgr->getOutputData('app_user_id')) ?>
                  </td>
                </tr>
<?php if ($mgr->wire_kbn == WIRE_KBN_WLESS && $mgr->getOutputData('wireless_id') != "") { ?>
                <tr>
                  <th width="120">無線用接続ID</th>
                  <td colspan="3"><?php echo $mgr->getOutputData('wireless_id') ?></td>
                </tr>
<?php } ?>
<?php if ($mgr->is_vdi_app && ($mgr->isVlanAdminUser() || $mgr->isAdminUser())) { ?>
                <tr>
                  <th>仮想環境での利用</th>
                  <td colspan="3"><div class="CheckBoxTab">
                    <table border="0" cellpadding="0" cellspacing="0">
                      <tr>
<?php   if ($mgr->is_vdi_only_edit_mode) { ?>
                        <td><input type="checkbox" name="use_sbc" id="use_sbc" value="1" <?php echo $mgr->getCheckData('use_sbc', '1') ?> onClick="changeUseSbcFlg();" /></td>
<?php   } else { ?>
                        <td><input type="checkbox" name="use_sbc" id="use_sbc" value="1" <?php echo $mgr->getCheckData('use_sbc', '1') ?> <?php echo ($mgr->is_agree_mode ? "" : "disabled") ?> /></td>
<?php   } ?>
                        <td><label for="use_sbc">仮想環境で利用する（仮想環境で利用する場合「プリンタ名」欄にメーカー型番を入力してください。）</label></td>
                      </tr>
                    </table>
                  </div></td>
                </tr>
<?php } ?>
              </table>
