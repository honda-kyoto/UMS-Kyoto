              <table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
                <tr>
                  <th width="120" scope="col">機器種別<span class="hissu">*</span></th>
                  <td width="120" scope="col">
<?php if ($mgr->getRequestData('app_id') != "") { ?>
                    <input type="hidden" name="app_type_id" id="app_type_id" value="<?php echo $mgr->getRequestData('app_type_id') ?>">
                    <select name="tmp_app_type_id" id="tmp_app_type_id" disabled>
<?php } else { ?>
                    <select name="app_type_id" id="app_type_id" onchange="formSubmit('reload', '<?php echo $_SERVER['SCRIPT_NAME'] ?>');">
<?php } ?>
                      <option value="">----</option>
                      <?php echo $mgr->getSelectList('app_type_id') ?>
                    </select>
                  </td>
                  <th width="100" scope="col">名称<span class="hissu">*</span></th>
                  <td scope="col"><input name="app_name" type="text" id="app_name" size="30" value="<?php echo $mgr->getRequestData('app_name') ?>" /></td>
                </tr>
<?php if ($mgr->is_wire_free || $mgr->is_ip_free) { ?>
                <tr>
<?php   if ($mgr->is_wire_free) { ?>
                  <th scope="col">有線／無線<span class="hissu">*</span></th>
<?php     if ($mgr->is_ip_free) { ?>
                  <td scope="col">
<?php     } else { ?>
                  <td scope="col" colspan="3">
<?php     } ?>
                    <select name="wire_kbn" id="wire_kbn" onchange="formSubmit('reload', '<?php echo $_SERVER['SCRIPT_NAME'] ?>');">
                      <option value="">----</option>
                      <?php echo $mgr->getSelectList('wire_kbn') ?>
                    </select>
                  </td>
<?php   } ?>
<?php   if ($mgr->is_ip_free) { ?>
                  <th scope="col">IPアドレス<span class="hissu">*</span></th>
<?php     if ($mgr->is_wire_free) { ?>
                  <td scope="col">
<?php     } else { ?>
                  <td scope="col" colspan="3">
<?php     } ?>
                    <select name="ip_kbn" id="ip_kbn">
                      <option value="">----</option>
                      <?php echo $mgr->getSelectList('ip_kbn') ?>
                    </select>
                  </td>
<?php   } ?>
                </tr>
<?php } ?>
                <tr>
                  <th width="120">設置場所<span class="hissu">*</span>[<a class="vlanmng" href="#" title="VLAN管理者について|病棟の方は情報統括部を選択して下さい。">？</a>]</th>
                  <td colspan="3">
                    <select name="vlan_ridge_id" id="vlan_ridge_id" onchange="vlanRidgeChange(this.value);" style="width:100px;">
                      <option value="">---棟屋---</option>
                      <?php echo $mgr->getSelectList('vlan_ridge_id') ?>
                    </select><div class="floatLeft" id="vlanRidgeJs"></div>
                    <select name="vlan_floor_id" id="vlan_floor_id" onchange="vlanFloorChange(this.value);" <?php echo ($mgr->request['vlan_ridge_id'] == "" ? "disabled" : "") ?> style="width:80px;">
                      <option value="">---階---</option>
                      <?php echo $mgr->getSelectList('vlan_floor_id', '', $mgr->request['vlan_ridge_id']) ?>
                    </select><div class="floatLeft" id="vlanFloorJs"></div>
<?php if ($mgr->wire_kbn == WIRE_KBN_WLESS) { ?>
                    <select name="vlan_room_id" id="vlan_room_id" <?php echo ($mgr->request['vlan_floor_id'] == "" ? "disabled" : "") ?> style="width:140px;">
<?php } else { ?>
                    <select name="vlan_room_id" id="vlan_room_id" onchange="vlanRoomChange(this.value);" <?php echo ($mgr->request['vlan_floor_id'] == "" ? "disabled" : "") ?> style="width:140px;">
<?php } ?>
                      <option value="">---部屋---</option>
                      <?php echo $mgr->getSelectList('vlan_room_id', '', $mgr->request['vlan_floor_id']) ?>
                    </select><div class="floatLeft" id="vlanRoomJs"></div>
                    <select name="vlan_id" id="vlan_id" <?php echo (($mgr->request['vlan_room_id'] == "" || $mgr->wire_kbn == WIRE_KBN_WLESS) ? "disabled" : "") ?> style="width:160px;">
                      <option value="">---VLAN管理者---</option>
                      <?php echo $mgr->getSelectList('vlan_id', '', $mgr->request['vlan_room_id']) ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <th width="120">MACアドレス<span class="hissu">*</span>[<a id="sticky" href="#" rel="mac/mac_addr.html">？</a>]</th>
                  <td colspan="3"><input name="mac_addr" type="text" id="mac_addr" size="20" maxlength="17" value="<?php echo $mgr->getRequestData('mac_addr') ?>" />&nbsp;<span class="inputRule">(例：00:2d:4e:09:e4:12　00-2D-4E-09-E4-12　など)</span></td>
                </tr>
<?php if ($mgr->isAdminUser() && $mgr->ip_kbn == IP_KBN_FIXD) { ?>
                <tr>
                  <th width="120">IPアドレス<span class="hissu">*</span></th>
                  <td colspan="3"><input name="ip_addr" type="text" id="ip_addr" size="20" maxlength="15" value="<?php echo $mgr->getRequestData('ip_addr') ?>" />&nbsp;<span class="inputRule">(例：192.168.110.12)</span></td>
                </tr>
<?php } ?>
                <tr>
                  <th width="120">備考</th>
                  <td colspan="3"><textarea name="note" cols="40" rows="4" id="note">
<?php echo $mgr->getRequestData('note') ?></textarea></td>
                </tr>
<?php if ($mgr->isUserCtrlUser()) { ?>
                <tr>
                  <th width="120">機器利用者</th>
                  <td colspan="3">
                    <span id="appUserName"><?php echo $mgr->getUserName($mgr->getRequestData('app_user_id')) ?></span>
                    <input type="hidden" name="app_user_id" id="app_user_id" value="<?php echo $mgr->getRequestData('app_user_id') ?>">
                    [<a href="javascript:;" onclick="searchAppUser();">検索</a>]
                  </td>
                </tr>
<?php } ?>
<?php if ($mgr->wire_kbn == WIRE_KBN_WLESS && $mgr->getRequestData('wireless_id') != "") { ?>
                <tr>
                  <th width="120">無線用接続ID</th>
                  <td colspan="3"><?php echo $mgr->getRequestData('wireless_id') ?></td>
                </tr>
<?php } ?>
<?php if ($mgr->is_vdi_app && ($mgr->isVlanAdminUser() || $mgr->isAdminUser())) { ?>
                <tr>
                  <th>仮想環境での利用</th>
                  <td colspan="3"><div class="CheckBoxTab">
                    <table border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td><input type="checkbox" name="use_sbc" id="use_sbc" value="1" <?php echo $mgr->getCheckData('use_sbc', '1') ?> /></td>
                        <td><label for="use_sbc">仮想環境で利用する（仮想環境で利用する場合「プリンタ名」欄にメーカー型番を入力してください。）</label></td>
                      </tr>
                    </table>
                  </div></td>
                </tr>
<?php } ?>
              </table>
