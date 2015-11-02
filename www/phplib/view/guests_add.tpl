<?php include("view/head.tpl") ?>
      <h3>■必要事項を入力して「登録」ボタンをクリックしてください。</h3>
      <table border="0" cellpadding="3" cellspacing="3">
        <tr>
          <td><div class="inputComment">(*)は必須項目です。</div></td>
        </tr>
        <tr>
          <td>
            <table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
            <tr>
              <th width="120" scope="col">ゲスト氏名<span class="hissu">*</span></th>
              <td scope="col"><input name="guest_name" type="text" id="guest_name" size="30" value="<?php echo $mgr->getRequestData('guest_name') ?>" /></td>
            </tr>
            <tr>
              <th>会社名<span class="hissu">*</span></th>
              <td><input name="company_name" type="text" id="company_name" size="20" value="<?php echo $mgr->getRequestData('company_name') ?>" /></td>
            </tr>
            <tr>
              <th>所属<span class="hissu">*</span></th>
              <td><input name="belong_name" type="text" id="belong_name" size="20" value="<?php echo $mgr->getRequestData('belong_name') ?>" /></td>
            </tr>
            <tr>
              <th>電話番号<span class="hissu">*</span></th>
              <td><input name="telno" type="text" id="telno" size="20" value="<?php echo $mgr->getRequestData('telno') ?>" /></td>
            </tr>
            <tr>
              <th>MACアドレス[<a id="sticky" href="#" rel="mac/mac_addr.html">？</a>]</th>
              <td><input name="mac_addr" type="text" id="mac_addr" size="20" maxlength="17" value="<?php echo $mgr->getRequestData('mac_addr') ?>" />&nbsp;<span class="inputRule">(例：00:2d:4e:09:e4:12　00-2D-4E-09-E4-12　など)</span></td>
            </tr>
            <tr>
              <th>用途<span class="hissu">*</span></th>
              <td><textarea name="usage" cols="40" rows="4" id="usage">
<?php echo $mgr->getRequestData('usage') ?></textarea></td>
            </tr>
            <tr>
              <th>備考</th>
              <td><textarea name="note" cols="40" rows="4" id="note">
<?php echo $mgr->getRequestData('note') ?></textarea></td>
            </tr>
          </table>
          </td>
        </tr>
        <tr>
          <td><input type="button" value="　　　登　　　録　　　" onclick="addGuest();" />
            <input type="button" value="　　　リセット　　　" onclick="resetGuest();" /></td>
        </tr>
      </table>
<?php include("view/foot.tpl") ?>

