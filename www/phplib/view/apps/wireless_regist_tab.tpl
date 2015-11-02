              <input type="hidden" name="target_vlan_id" id="target_vlan_id" value="">
              <table border="0" cellpadding="2" cellspacing="0" class="listTab">
                <tr>
                  <th scope="col">VLAN選択</th>
                  <th width="80" scope="col">追加／削除</th>
                </tr>
                <tr>
                  <td class="add">
                    <select name="wl_vlan_ridge_id" id="wl_vlan_ridge_id" onchange="vlanRidgeChange(this.value, 'wl_');" style="width:100px;">
                      <option value="">---棟屋---</option>
                      <?php echo $mgr->getSelectList('wl_vlan_ridge_id') ?>
                    </select><div class="floatLeft" id="wl_vlanRidgeJs"></div>
                    <select name="wl_vlan_floor_id" id="wl_vlan_floor_id" onchange="vlanFloorChange(this.value, 'wl_');" <?php echo ($mgr->request['wl_vlan_ridge_id'] == "" ? "disabled" : "") ?> style="width:80px;">
                      <option value="">---階---</option>
                      <?php echo $mgr->getSelectList('wl_vlan_floor_id', '', $mgr->request['wl_vlan_ridge_id']) ?>
                    </select><div class="floatLeft" id="wl_vlanFloorJs"></div>
                    <select name="wl_vlan_room_id" id="wl_vlan_room_id" onchange="vlanRoomChange(this.value, 'wl_');" <?php echo ($mgr->request['wl_vlan_floor_id'] == "" ? "disabled" : "") ?> style="width:140px;">
                      <option value="">---部屋---</option>
                      <?php echo $mgr->getSelectList('wl_vlan_room_id', '', $mgr->request['wl_vlan_floor_id']) ?>
                    </select><div class="floatLeft" id="wl_vlanRoomJs"></div>
                    <select name="wl_vlan_id" id="wl_vlan_id" <?php echo ($mgr->request['wl_vlan_room_id'] == "" ? "disabled" : "") ?> style="width:160px;">
                      <option value="">---VLAN管理者---</option>
                      <?php echo $mgr->getSelectList('wl_vlan_id', '', $mgr->request['wl_vlan_room_id']) ?>
                    </select>
                  </td>
                  <td class="add" align="center">[<a href="javascript:;" onclick="joinWirelessVlan();">追加</a>]</td>
                </tr>
<?php if (is_array($mgr->output['vlan_area_name'])) { ?>
<?php   foreach ($mgr->output['vlan_area_name'] AS $vlan_id => $dummy) { ?>
                <tr>
                  <td>
                    <?php echo $mgr->getOutputData('vlan_area_name', $vlan_id) ?>
<?php     if ($mgr->getOutputData('agree_flg', $vlan_id) == '1' && $mgr->getOutputData('busy_flg', $vlan_id) == '1') { ?>
                    　<span style="color:#00f;">使用中</span>
<?php     } ?>
                  </td>
                  <td align="center">
<?php     if ($mgr->getOutputData('busy_flg', $vlan_id) == '1') { ?>
                    -
<?php     } else { ?>
                    [<a href="javascript:;" onclick="defectWirelessVlan(<?php echo $vlan_id ?>);">削除</a>]
<?php     } ?>
                  </td>
                </tr>
<?php   } ?>
<?php } ?>
              </table>
