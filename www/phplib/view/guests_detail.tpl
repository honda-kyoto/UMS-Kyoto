<?php include("view/head.tpl") ?>
      <input type="hidden" name="guest_id" id="guest_id" value="<?php echo $mgr->getRequestData('guest_id') ?>">
      <h3>■以下の内容で登録されています。</h3>
      <table border="0" cellpadding="3" cellspacing="3">
        <tr>
          <td>
            <table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
            <tr>
              <th width="120" scope="col">ゲスト氏名</th>
              <td scope="col"><?php echo $mgr->getOutputData('guest_name') ?></td>
            </tr>
            <tr>
              <th>会社名</th>
              <td><?php echo $mgr->getOutputData('company_name') ?></td>
            </tr>
            <tr>
              <th>所属</th>
              <td><?php echo $mgr->getOutputData('belong_name') ?></td>
            </tr>
            <tr>
              <th>電話番号</th>
              <td><?php echo $mgr->getOutputData('telno') ?></td>
            </tr>
            <tr>
              <th>MACアドレス</th>
              <td><?php echo $mgr->getOutputData('mac_addr') ?></td>
            </tr>
            <tr>
              <th>用途</th>
              <td><?php echo nl2br($mgr->getOutputData('usage')) ?></td>
            </tr>
            <tr>
              <th>備考</th>
              <td><?php echo nl2br($mgr->getOutputData('note')) ?></td>
            </tr>
            <tr>
              <th>接続ID</th>
              <td><?php echo $mgr->getOutputData('wireless_id') ?></td>
            </tr>
            <tr>
              <th>パスワード</th>
              <td><span id="passwd">********</span>&nbsp;&nbsp;[<a id="passwd_view" href="javascript:;" onclick="viewPasswd();">表示</a><a id="passwd_hide" href="javascript:;" onclick="hidePasswd();" style="display:none;">隠す</a>]</td>
            </tr>
<?php if ($mgr->isAdminUser()) { ?>
            <tr>
              <th>登録者</th>
              <td><?php echo $mgr->getOutputData('entry_name') ?></td>
            </tr>
<?php } ?>
            <tr>
              <th>登録日時</th>
              <td><?php echo $mgr->getOutputData('entry_time') ?></td>
            </tr>
            <tr>
              <th>有効期限</th>
              <td><?php echo $mgr->getOutputData('over_time') ?><?php echo ($mgr->getOutputData('over_flg') == '1' ? '　<span style="color:#f00;">期限切れ</span>' : '') ?></td>
            </tr>
          </table>
          </td>
        </tr>
        <tr>
          <td>
            <input type="button" value="　　　削　　　除　　　" onclick="deleteGuest();" <?php echo ($mgr->getOutputData('over_flg') == '1' ? 'disabled' : '') ?> />
            <input type="button" value="　　　一覧へ戻る　　　" onclick="returnList();" /></td>
        </tr>
      </table>
<?php include("view/foot.tpl") ?>

