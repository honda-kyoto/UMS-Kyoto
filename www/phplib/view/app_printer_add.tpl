<?php include("view/head.tpl") ?>
      <table cellspacing="0" >
          <tr>
            <td>■端末を選択し、割り付けるプリンタを設定して「登録」をクリックしてください。仮想環境でのプリンタ一覧に表示されます。</td>
          </tr>
          <tr>
            <td><div class="inputComment">(*)は必須項目です。</div></td>
          </tr>
          <tr>
            <td>
              <table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
                <tr>
                  <th width="120">端末<span class="hissu">*</span></th>
                  <td width="220"><div class="CheckBoxTab">
                      <table border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td nowrap="nowrap"><select name="tm_vlan_ridge_id" id="tm_vlan_ridge_id" onchange="vlanRidgeChange(this.value, 'tm_');" style="width:100px;">
              <option value="">---棟屋---</option>
                <?php echo $mgr->getSelectList('tm_vlan_ridge_id') ?>
            </select><div class="floatLeft" id="tm_vlanRidgeJs"></div>
            <select name="tm_vlan_floor_id" id="tm_vlan_floor_id" onchange="vlanFloorChange(this.value, 'tm_');" <?php echo ($mgr->request['tm_vlan_ridge_id'] == "" ? "disabled" : "") ?> style="width:80px;">
              <option value="">---階---</option>
              <?php echo $mgr->getSelectList('tm_vlan_floor_id', '', $mgr->request['tm_vlan_ridge_id']) ?>
            </select><div class="floatLeft" id="tm_vlanFloorJs"></div>
            <select name="tm_vlan_room_id" id="tm_vlan_room_id" <?php echo ($mgr->request['tm_vlan_floor_id'] == "" ? "disabled" : "") ?> style="width:140px;">
              <option value="">---部屋---</option>
              <?php echo $mgr->getSelectList('tm_vlan_room_id', '', $mgr->request['tm_vlan_floor_id']) ?>
            </select></td>
                          <td>&nbsp;</td>
                          <td><input name="unallocated_only" type="checkbox" id="unallocated_only" value="1" <?php echo $mgr->getCheckData('unallocated_only', '1') ?> /></td>
                          <td nowrap="nowrap"><label for="unallocated_only">プリンタ未割付のみ</label></td>
                          <td nowrap="nowrap">&nbsp;</td>
                          <td nowrap="nowrap"><input type="button" value="　　　表示　　　" onclick="terminalSearch();" /></td>
                        </tr>
                        <tr>
                          <td colspan="6" nowrap="nowrap">&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="6" nowrap="nowrap"><select name="terminal_id" id="terminal_id" style="width:400px;" onchange="terminalSelect();">
                            <option value="">---端末---</option>
                            <?php echo $mgr->getTerminalList() ?>
                          </select></td>
                        </tr>
                      </table>
                    </div>
                    </td>
                </tr>
                <tr>
                  <th width="120">プリンタ<span class="hissu">*</span></th>
                  <td><div class="CheckBoxTab">
                    <table border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td nowrap="nowrap"><select name="dv_vlan_ridge_id" id="dv_vlan_ridge_id" onchange="vlanRidgeChange(this.value, 'dv_');" style="width:100px;">
              <option value="">---棟屋---</option>
                <?php echo $mgr->getSelectList('dv_vlan_ridge_id') ?>
            </select><div class="floatLeft" id="dv_vlanRidgeJs"></div>
            <select name="dv_vlan_floor_id" id="dv_vlan_floor_id" onchange="vlanFloorChange(this.value, 'dv_');" <?php echo ($mgr->request['dv_vlan_ridge_id'] == "" ? "disabled" : "") ?> style="width:80px;">
              <option value="">---階---</option>
              <?php echo $mgr->getSelectList('dv_vlan_floor_id', '', $mgr->request['dv_vlan_ridge_id']) ?>
            </select><div class="floatLeft" id="dv_vlanFloorJs"></div>
            <select name="dv_vlan_room_id" id="dv_vlan_room_id" <?php echo ($mgr->request['dv_vlan_floor_id'] == "" ? "disabled" : "") ?> style="width:140px;">
              <option value="">---部屋---</option>
              <?php echo $mgr->getSelectList('dv_vlan_room_id', '', $mgr->request['dv_vlan_floor_id']) ?>
            </select></td>
                        <td>&nbsp;</td>
                        <td><input type="button" value="　　　表示　　　" onclick="reView();" /></td>
                        </tr>
                      <tr>
                        <td colspan="3" nowrap="nowrap">&nbsp;</td>
                        </tr>
                        </table>
                        <table border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td nowrap="nowrap"><select name="select_id[]" size="20" multiple="multiple" id="select_id" style="width:300px;">
                          <?php echo $mgr->getDeviceList() ?>
                        </select></td>
                        <td>&nbsp;</td>
                        <td><input type="button" name="right" id="right" value="　　割付→　　" />
                          <br />
<br />
<input type="button" name="left" id="left" value="　　←解除　　" /></td>
                        <td>&nbsp;</td>
                        <td valign="top" nowrap="nowrap"><select name="device_id[]" size="20" multiple="multiple" id="device_id" style="width:300px;">
                          <?php echo $mgr->getDeviceSelectedList() ?>
                        </select></td>
                        <td nowrap="nowrap">&nbsp;</td>
                        <td valign="top" nowrap="nowrap"><input type="button" value="　上へ　" onclick="moveUpElement();" />
                          <br />
                          <br />
                          <input type="button" value="　下へ　" onclick="moveDownElement();" /></td>
                        </tr>
                      </table>
                  </div></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="20" /></td>
          </tr>
          <tr>
            <td><input type="button" value="　　　登　　　録　　　" onclick="setDevice();" />
              <input type="button" value="　　　リセット　　　" onclick="resetDevice();" />
            </td>
          </tr>
        </table
<?php include("view/foot.tpl") ?>

