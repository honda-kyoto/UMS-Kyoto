<?php include("view/head.tpl") ?>
      <input type="hidden" name="mlist_id" id="mlist_id" value="<?php echo $mgr->getRequestData('mlist_id') ?>">
      <input type="hidden" name="target_addr" id="target_addr" value="">
      <div id="topBtnArea">
        <input type="button" value="　　一覧に戻る　　" onclick="returnList();" />
      </div>
      <div style="clear:both;"></div>
      <table border="0" cellpadding="3" cellspacing="3">
<?php if ($mgr->has_work_data) { ?>
        <tr>
          <td>
            <div class="warningComment">※まだ登録は完了していません。最後に必ず「設定完了」ボタンを押してください。</div>
          </td>
        </tr>
<?php } ?>
        <tr>
          <td>
            <h3>■ヘッダ情報</h3>
            <table border="0" cellpadding="2" cellspacing="0" class="listTab">
              <tr>
                <th>アカウント</th>
                <th>名称</th>
              </tr>
              <tr>
                <td><?php echo $mgr->getOutputData('mlist_acc') ?></td>
                <td><?php echo $mgr->getOutputData('mlist_name') ?></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td><img src="image/space.gif" width="1" height="15" /></td>
        </tr>
<?php if ($mgr->mlist_kbn == MLIST_KBN_AUTO) { ?>
<?php   include("view/mlists/input_auto_cond.tpl") ?>
<?php } else { ?>
<?php   include("view/mlists/input_members.tpl") ?>
<?php } ?>
        <tr>
          <td>
<?php if ($mgr->mlist_kbn == MLIST_KBN_AUTO) { ?>
            <input type="button" value="　　　　設定完了　　　　" onclick="commitWork();" <?php echo ($mgr->has_work_data ? "" : "disabled") ?> />
            <input type="button" value="　　　キャンセル　　　" onclick="cancelWork();" <?php echo ($mgr->has_work_data ? "" : "disabled") ?> />
            <input type="button" value="　　　メンバー一覧を表示　　　" onclick="viewAutoMembers();" />
<?php } ?>
            <input type="button" value="　　一覧に戻る　　" onclick="returnList();" />
          </td>
        </tr>
      </table>
<?php include("view/foot.tpl") ?>

