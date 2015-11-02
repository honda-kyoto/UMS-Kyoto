<?php include("view/head.tpl") ?>
        <input type="hidden" name="mlist_id" id="mlist_id" value="<?php echo $mgr->getRequestData('mlist_id') ?>">
        <input type="hidden" name="entry_no" id="entry_no" value="<?php echo $mgr->getRequestData('entry_no') ?>">
        <table cellspacing="0" >
          <tr>
            <td>■現在以下の内容で申請されています。必要事項を記入して「承認」または「却下」を押してください。</td>
          </tr>
          <tr>
            <td><div class="inputComment">※却下の場合は必ず却下理由を記載してください。</div></td>
          </tr>
          <tr>
            <td>
<?php include("view/mlists/view_tab.tpl") ?>
            </td>
          </tr>
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="20" /></td>
          </tr>
          <tr>
            <td>■申請状況</td>
          </tr>
          <tr>
            <td>
<?php include("view/common_entry_tab.tpl") ?>
            </td>
          </tr>
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="20" /></td>
          </tr>
          <tr>
            <td>
              <input type="button" value="　　　承　　　認　　　" onclick="agreeMlist();" />
              <input type="button" value="　　　却　　　下　　　" onclick="rejectMlist();" />
              <input type="button" value="　　一覧に戻る　　" onclick="returnList();" /></td>
          </tr>
        </table>
<?php include("view/foot.tpl") ?>
