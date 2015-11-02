        <table border="0" cellpadding="3" cellspacing="3">
          <tr>
            <td><table width="700" border="0" cellpadding="5" cellspacing="0" class="inputTab">
              <tr>
                <th colspan="4">基本情報</th>
              </tr>
              <tr>
                <th>氏名</th>
                <td><?php echo $mgr->getOutputData('kanjisei') ?>　<?php echo $mgr->getOutputData('kanjimei') ?>（<?php echo $mgr->getOutputData('kanasei') ?>　<?php echo $mgr->getOutputData('kanamei') ?>）</td>
                <th width="100">内線</th>
                <td><?php echo $mgr->getOutputData('naisen') ?></td>
              </tr>
              <tr>
                <th width="105">組織</th>
                <td colspan="3"><?php echo $mgr->getOutputData('belong_name') ?>　<?php echo $mgr->getOutputData('job_name') ?>　<?php echo $mgr->getOutputData('post_name') ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="5" /></td>
          </tr>
          <tr>
            <td><hr /></td>
          </tr>
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="5" /></td>
          </tr>
        </table>
