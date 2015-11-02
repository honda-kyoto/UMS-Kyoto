<?php include("view/head.tpl") ?>
      <input type="hidden" name="mlist_id" id="mlist_id" value="<?php echo $mgr->getRequestData('mlist_id') ?>">
      <input type="hidden" name="entry_no" id="entry_no" value="<?php echo $mgr->getRequestData('entry_no') ?>">
      <h3>■必要事項を入力して「申請」ボタンをクリックしてください。</h3>
      <table border="0" cellpadding="3" cellspacing="3">
        <tr>
          <td><div class="inputComment">(*)は必須項目です。</div></td>
        </tr>
        <tr>
          <td>
<?php include("view/mlists/regist_tab.tpl") ?>
          </td>
        </tr>
        <tr>
          <td>
            <input type="button" value="　　　申　　　請　　　" onclick="entryMlist();" />
            <input type="button" value="　　　リセット　　　" onclick="resetMlist();" />
<?php if ($mgr->getRequestData('entry_no') != "") { ?>
              <input type="button" value="　　　戻　　る　　　" onclick="returnPendingMlist();" />
<?php } else if ($mgr->getRequestData('app_id') != "") { ?>
              <input type="button" value="　　　戻　　る　　　" onclick="returnList();" />
<?php } ?>
          </td>
        </tr>
      </table>
<?php include("view/foot.tpl") ?>
