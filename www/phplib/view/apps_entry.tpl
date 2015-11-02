<?php include("view/head.tpl") ?>
        <input type="hidden" name="app_id" id="app_id" value="<?php echo $mgr->getRequestData('app_id') ?>">
        <input type="hidden" name="entry_no" id="entry_no" value="<?php echo $mgr->getRequestData('entry_no') ?>">
        <table cellspacing="0" >
          <tr>
            <td>■必要事項を入力して「申請」ボタンをクリックしてください。</td>
          </tr>
          <tr>
            <td><div class="inputComment">(*)は必須項目です。</div></td>
          </tr>
          <tr>
            <td>
<?php include("view/apps/regist_tab.tpl") ?>
            </td>
          </tr>
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="20" /></td>
          </tr>
<?php if ($mgr->wire_kbn == WIRE_KBN_WLESS) { ?>
          <tr>
            <td>■無線LAN利用申請</td>
          </tr>
          <tr>
            <td><div class="inputComment">※無線VLANは複数選択できますが申請は１件ずつ行ってください。</div></td>
          </tr>
          <tr>
            <td>
              <table border="0" cellpadding="2" cellspacing="0" class="listTab">
                <tr>
                  <th scope="col">VLAN選択</th>
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
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="20" /></td>
          </tr>
<?php   if (is_array($mgr->output['vlan_area_name']) && count($mgr->output['vlan_area_name']) > 0) { ?>
          <tr>
            <td>■利用中無線LAN</td>
          </tr>
          <tr>
            <td>
              <input type="hidden" name="target_vlan_id" id="target_vlan_id" value="">
              <table border="0" cellpadding="2" cellspacing="0" class="listTab">
                <tr>
                  <th scope="col">VLAN</th>
                </tr>
<?php     foreach ($mgr->output['vlan_area_name'] AS $vlan_id => $data) { ?>
                <tr>
                  <td>
                    <?php echo $mgr->getOutputData('vlan_area_name', $vlan_id) ?>
<?php       if ($mgr->getOutputData('agree_flg', $vlan_id) == '1' && $mgr->getOutputData('busy_flg', $vlan_id) == '1') { ?>
                    　<span style="color:#00f;">使用中</span>
<?php       } ?>
                  </td>
                  <!--<td align="center">[<a href="javascript:;" onclick="defectWirelessVlan(<?php echo $vlan_id ?>);">削除</a>]</td>-->
                </tr>
<?php     } ?>
              </table>
            </td>
          </tr>
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="20" /></td>
          </tr>
<?php   } ?>
<?php } ?>
          <tr>
            <td><input type="button" value="　　　申　　　請　　　" onclick="entryApp();" />
              <input type="button" value="　　　リセット　　　" onclick="resetApp();" />
<?php if ($mgr->getRequestData('entry_no') != "") { ?>
              <input type="button" value="　　　戻　　る　　　" onclick="returnPendingApp();" />
<?php } else if ($mgr->getRequestData('app_id') != "") { ?>
              <input type="button" value="　　　戻　　る　　　" onclick="returnList();" />
<?php } ?>
            </td>
          </tr>
        </table>
<?php include("view/foot.tpl") ?>
