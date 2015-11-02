<?php include("view/head.tpl") ?>
      <table cellspacing="0" >
          <tr>
            <td>■割り付けるプリンタを選択して「登録」をクリックしてください。仮想環境でのプリンタ一覧に表示されます。</td>
          </tr>
          <tr>
            <td><div class="inputComment">(*)は必須項目です。</div></td>
          </tr>
          <tr>
            <td>
              <table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
                <tr>
                  <th width="120">プリンタ<span class="hissu">*</span></th>
                  <td><div class="CheckBoxTab">
                    <table border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td nowrap="nowrap"><select name="vlan_ridge_id" id="vlan_ridge_id" onchange="vlanRidgeChange(this.value);" style="width:100px;">
              <option value="">---棟屋---</option>
                <?php echo $mgr->getSelectList('vlan_ridge_id') ?>
            </select><div class="floatLeft" id="vlanRidgeJs"></div>
            <select name="vlan_floor_id" id="vlan_floor_id" onchange="vlanFloorChange(this.value);" <?php echo ($mgr->request['vlan_ridge_id'] == "" ? "disabled" : "") ?> style="width:80px;">
              <option value="">---階---</option>
              <?php echo $mgr->getSelectList('vlan_floor_id', '', $mgr->request['vlan_ridge_id']) ?>
            </select><div class="floatLeft" id="vlanFloorJs"></div>
            <select name="vlan_room_id" id="vlan_room_id" <?php echo ($mgr->request['vlan_floor_id'] == "" ? "disabled" : "") ?> style="width:140px;">
              <option value="">---部屋---</option>
              <?php echo $mgr->getSelectList('vlan_room_id', '', $mgr->request['vlan_floor_id']) ?>
            </select></td>
                        <td>&nbsp;</td>
                        <td><input type="button" value="　　　表示　　　" onclick="formSubmit('input');" /></td>
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

