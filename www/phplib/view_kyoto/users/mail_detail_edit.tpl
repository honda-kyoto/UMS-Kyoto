        <input type="hidden" name="list_no" id="list_no" value="">
<?php include("view/users/detail_common_header.tpl") ?>
        <table border="0" cellpadding="3" cellspacing="3">
          <tr>
            <td>■メールアカウント設定</td>
          </tr>
          <tr>
            <td><table border="0" cellpadding="2" cellspacing="0" class="listTab">
              <tr>
                <th scope="col">メールアカウント</th>
                <th width="80" scope="col">英字姓</th>
                <th width="80" scope="col">英字名</th>
                <th width="80" scope="col">追加／削除</th>
              </tr>
              <tr>
                <td class="add"><input name="mail_acount" type="text" id="mail_acount" size="20" value="<?php echo $mgr->getRequestData('mail_acount') ?>" />@kuhp.kyoto-u.ac.jp</td>
                <td class="add"><input name="eijisei" type="text" id="eijisei" size="6" value="<?php echo $mgr->getRequestData('eijisei') ?>" /></td>
                <td class="add"><input name="eijimei" type="text" id="eijimei" size="6" value="<?php echo $mgr->getRequestData('eijimei') ?>" /></td>
                <td class="add" align="center">[<a href="javascript:;" onclick="addMailAcount();">追加</a>]</td>
              </tr>
<?php if (is_array($mgr->output['sendon_list'])) { ?>
<?php   foreach ($mgr->output['sendon_list'] AS $list_no => $addr) { ?>
              <tr>
                <td><?php echo $mgr->getOutputData('sendon_list', $list_no) ?>test@kuhp.kyoto-u.ac.jp</td>
                <td><?php echo $mgr->getOutputData('sendon_list', $list_no) ?>test</td>
                <td><?php echo $mgr->getOutputData('sendon_list', $list_no) ?>test</td>
                <td align="center">[<a href="javascript:;" onclick="delSendonAddr(<?php echo $list_no ?>);">削除</a>]</td>
              </tr>
<?php   } ?>
<?php } ?>
            </table></td>
        </table>
