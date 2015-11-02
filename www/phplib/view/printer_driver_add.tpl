<?php include("view/head.tpl") ?>
      <table cellspacing="0" >
          <tr>
            <td>■仮想環境で使用するプリンタにVDIマスタに登録したプリンタドライバ名を割り付けます。<br />
              <font color="#FFF">■</font>割り付けるプリンタを選択して、プリンタドライバ名を入力し「登録」をクリックしてください。</td>
          </tr>
          <tr>
            <td><div class="inputComment">(*)は必須項目です。</div></td>
          </tr>
          <tr>
            <td><div class="inputComment">※ドライバ名割当済みのプリンタにドライバ名を割り当てると、古い情報が削除され新しい割り当て情報に変更されます。</div></td>
          </tr>
          <tr>
            <td>
              <table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
                <tr>
                  <th width="120">プリンタ<span class="hissu">*</span></th>
                  <td width="220"><div class="CheckBoxTab">
                      <table border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td nowrap="nowrap">


                            <select name="vlan_ridge_id" id="vlan_ridge_id" onchange="vlanRidgeChange(this.value);" style="width:100px;">
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
            </select>

                          </td>
                          <td>&nbsp;</td>
                          <td><input name="unallocated_only" type="checkbox" id="unallocated_only" value="1" <?php echo $mgr->getCheckData('unallocated_only', '1') ?> /></td>
                          <td nowrap="nowrap"><label for="unallocated_only">ドライバ名未割当のみ</label></td>
                          <td nowrap="nowrap">&nbsp;</td>
                          <td nowrap="nowrap"><input type="button" value="　　　表示　　　" onclick="searchPrinter();" /></td>
                        </tr>
                        <tr>
                          <td colspan="6" nowrap="nowrap">&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="6" nowrap="nowrap"><select name="app_id" id="app_id" style="width:400px;" onchange="printerSelect();">
                            <option value="">---プリンタ---</option>
                            <?php echo $mgr->getPrinterList() ?>
                          </select></td>
                        </tr>
                      </table>
                    </div>
                    </td>
                </tr>
                <tr>
                  <th width="120">ドライバ名<span class="hissu">*</span></th>
                  <td><select name="driver_name" id="driver_name" style="width:400px;">
                    <option value="">---</option>
                    <?php echo $mgr->getDriverList() ?>
                  </select></td>
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
            <td><input type="button" value="　　　登　　　録　　　" onclick="setDriverName();" />
              <input type="button" value="　　　リセット　　　" onclick="resetDriver();" />
            </td>
          </tr>
        </table>
<?php include("view/foot.tpl") ?>

