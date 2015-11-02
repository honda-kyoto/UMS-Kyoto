      <h3>■検索条件</h3>
      <table border="0" cellpadding="2" cellspacing="0" class="searchTab">
        <tr>
          <th>機器種別</th>
          <td><select name="app_type_id" id="app_type_id">
              <option value="">----</option>
              <?php echo $mgr->getSelectList('app_type_id') ?>
            </select></td>
          <th width="80">設置場所</th>
          <td colspan="3">
            <select name="vlan_ridge_id" id="vlan_ridge_id" onchange="vlanRidgeChange(this.value);" style="width:100px;">
              <option value="">---棟屋---</option>
                <?php echo $mgr->getSelectList('vlan_ridge_id') ?>
            </select><div class="floatLeft" id="vlanRidgeJs"></div>
            <select name="vlan_floor_id" id="vlan_floor_id" onchange="vlanFloorChange(this.value);" <?php echo ($mgr->request['vlan_ridge_id'] == "" ? "disabled" : "") ?> style="width:80px;">
              <option value="">---階---</option>
              <?php echo $mgr->getSelectList('vlan_floor_id', '', $mgr->request['vlan_ridge_id']) ?>
            </select><div class="floatLeft" id="vlanFloorJs"></div>
            <select name="vlan_room_id" id="vlan_room_id" onchange="vlanRoomChange(this.value);" <?php echo ($mgr->request['vlan_floor_id'] == "" ? "disabled" : "") ?> style="width:140px;">
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
          <th>MACアドレス</th>
          <td><input name="mac_addr" type="text" id="mac_addr" size="20" value="<?php echo $mgr->getRequestData('mac_addr') ?>"  /></td>
          <th width="80">IPアドレス</th>
          <td><input name="ip_addr" type="text" id="ip_addr" size="20" value="<?php echo $mgr->getRequestData('ip_addr') ?>"  /></td>
          <th width="80">名称</th>
          <td><input name="app_name" type="text" id="app_name" size="20" value="<?php echo $mgr->getRequestData('app_name') ?>"  /></td>
        </tr>
        <?php if ($mgr->req_only) { ?>
        <tr>
          <th>申請者(漢字・カナ)</th>
          <td colspan="5"><input name="entry_user_name" type="text" id="entry_user_name" size="20" value="<?php echo $mgr->getRequestData('entry_user_name') ?>"  /></td>
        </tr>
        <?php } else { ?>
        <tr>
          <th>利用者(漢字・カナ)</th>
          <td colspan="5"><input name="app_user_name" type="text" id="app_user_name" size="20" value="<?php echo $mgr->getRequestData('app_user_name') ?>"  /></td>
        </tr>
        <?php } ?>
<?php if (!$mgr->req_only && !$mgr->isAdminUser()) { ?>
        <tr>
          <th width="120">オプション</th>
          <td colspan="5">
            <div class="CheckBoxTab">
              <?php echo $mgr->getCheckBoxList('entry_kbn_status') ?>
            </div>
          </td>
        </tr>
<?php } ?>
      </table>
      <div class="searchBlock">
        <a href="javascript:search();"  /><img src="image/btn_search.gif" title="検索" /></a>
        <a href="javascript:clearCond();" /><img src="image/btn_clear.gif" title="条件クリア" /></a>
      </div>
