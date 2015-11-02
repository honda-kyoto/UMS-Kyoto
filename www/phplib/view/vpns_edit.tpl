<?php include("view/head.tpl") ?>
      <input type="hidden" name="vpn_id" id="vpn_id" value="<?php echo $mgr->getRequestData('vpn_id') ?>">
      <div id="topBtnArea">
        <input type="button" value="　　一覧に戻る　　" onclick="returnList();" />
      </div>
      <div style="clear:both;"></div>
      <h3>■必要事項を入力して「更新」ボタンをクリックしてください。</h3>
      <table border="0" cellpadding="3" cellspacing="3">
        <tr>
          <td><div class="inputComment">(*)は必須項目です。</div></td>
        </tr>
        <tr>
          <td>
<?php include("view/vpns/regist_tab.tpl") ?>
          </td>
        </tr>
        <tr>
          <td>
            <input type="button" value="　　　更　　　新　　　" onclick="editVpn();" />
            <input type="button" value="　　　リセット　　　" onclick="resetVpn();" />
            <input type="button" value="　　一覧に戻る　　" onclick="returnList();" />
          </td>
        </tr>
      </table>
<?php include("view/foot.tpl") ?>

