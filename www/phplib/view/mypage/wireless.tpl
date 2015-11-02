        <input type="hidden" name="app_id" id="app_id" value="">
        <table border="0" cellspacing="3" cellpadding="3">
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="20" /></td>
          </tr>
<?php if ($mgr->getOutputData('common_wireless_id') == "") { ?>
          <tr>
            <td>※現在設定されている無線LAN情報はありません。</td>
          </tr>
<?php } else { ?>
          <tr>
            <td>■共用無線ネットワーク用ID</td>
          </tr>
          <tr>
            <td><table border="0" cellpadding="2" cellspacing="0" class="listTab">
              <tr>
                <th scope="col">接続ID</th>
                <th scope="col">パスワード</th>
              </tr>
              <tr>
                <td><?php echo $mgr->getOutputData('common_wireless_id') ?></td>
                <td>統合IDのパスワード</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="20" /></td>
          </tr>
<?php   if (is_array($mgr->output['app_name']) && count($mgr->output['app_name']) > 0) { ?>
          <tr>
            <td>■機器別の個別VLAN割当</td>
          </tr>
          <tr>
            <td><div class="inputComment">
            ※下記のVLANへの接続は統合IDのパスワードをお使いください。
            </div></td>
          </tr>
          <tr>
            <td><div class="inputComment">
            ※VLANが複数割り当てられている場合は使用するVLANを変更することができます。
            </div></td>
          </tr>
          <tr>
            <td><table border="0" cellpadding="2" cellspacing="0" class="listTab">
              <tr>
                <th scope="col">機器名称</th>
                <th scope="col">接続ID</th>
                <th scope="col">割当VLAN</th>
                <th scope="col">VLAN変更</th>
              </tr>
<?php     foreach ($mgr->output['app_name'] AS $app_id => $dummy) { ?>
              <tr>
                <td><?php echo $mgr->getOutputData('app_name', $app_id) ?></td>
                <td><?php echo $mgr->getOutputData('wireless_id', $app_id) ?></td>
<?php       if ($mgr->getOutputData('wl_vlan_cnt', $app_id) == 1) { ?>
                <td><?php echo $mgr->getOutputData('vlan_area_name', $app_id) ?></td>
                <td align="center">-</td>
<?php       } else { ?>
                <td>
                  <select name="vlan_id[<?php echo $app_id ?>]" id="vlan_id_<?php echo $app_id ?>" style="width:400px;">
                    <?php echo $mgr->getWirelessVlanList($app_id) ?>
                  </select>
                </td>
                <td align="center">[<a href="javascript:;" onclick="changeWirelessVlan(<?php echo $app_id ?>);">変更</a>]</td>
<?php       } ?>
              </tr>
<?php     } ?>
            </table></td>
          </tr>
<?php   } ?>
<?php } ?>
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="20" /></td>
          </tr>
        </table>
