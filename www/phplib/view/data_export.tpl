<?php include("view/head.tpl") ?>
        <input type="hidden" name="user_role_id" id="user_role_id" value="">
        <input type="hidden" name="file_name" id="file_name" value="">
        <h3>■対象データの「データ出力」ボタンを押してください。</h3>
        <table width="100%" border="0" cellpadding="2" cellspacing="0" class="listTab" id="listTab">
          <tr>
            <th scope="col">対象データ</th>
            <th scope="col">データ出力</th>
          </tr>
<?php if (is_array($mgr->output['target_name']) && count($mgr->output['target_name']) > 0) { ?>
<?php   foreach ($mgr->output['target_name'] AS $id => $data) { ?>
          <tr>
            <td><?php echo $mgr->getOutputData('target_name', $id) ?></td>
            <td width="140" align="center"><input type="button" value="　　　データ出力　　　" onclick="outputData('<?php echo $id ?>')"> </td>
          </tr>
<?php     } ?>
<?php   } ?>
        </table>
        <table border="0" cellpadding="2" cellspacing="0" class="AjaxTableDisable" id="nowLoad">
          <tr>
            <td><img src="./image/loading.gif" alt="データ生成中です・・・" /></td>
          </tr>
          <tr>
            <td>データ生成中です・・・</td>
          </tr>
        </table>
<?php include("view/foot.tpl") ?>
