<?php     if ($mgr->getRequestData('edit_mode') == 'reserve') { ?>
                        <tr>
                          <th width="100" scope="col">反映日</th>
                          <td colspan="3" scope="col"><?php echo $mgr->getOutputData('send_date') ?></td>
                        </tr>
<?php     } ?>
                        <tr>
                          <th scope="col">ログインID</th>
                          <td scope="col" colspan="3"><?php echo $mgr->getOutputData('staffcode') ?></td>
                        </tr>
                        <tr>
                          <th width="100">漢字氏名</th>
                          <td><?php echo $mgr->getOutputData('kanjiname') ?></td>
                          <th>カナ氏名</th>
                          <td scope="col"><?php echo $mgr->getOutputData('kananame') ?></td>
                        </tr>
                        <tr>
                          <th>部署</th>
                          <td><?php echo $mgr->getOutputData('wardname') ?></td>
                          <th>職種</th>
                          <td><?php echo $mgr->getOutputData('professionname') ?></td>
                        </tr>
                        <tr>
                        <th>役職</th>
                          <td><?php echo $mgr->getOutputData('gradename') ?></td>
                          <th>診療グループ</th>
                          <td><?php echo $mgr->getOutputData('deptname') ?></td>
                        </tr>
                        <tr>
                          <th>予約項目コード</th>
                          <td><?php echo $mgr->getOutputData('appcode') ?>&nbsp;</td>
                          <th>有効期間</th>
                          <td><table border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td><?php echo $mgr->getOutputData('validstartdate') ?></td>
                                <td> 　～　 </td>
<?php if ($mgr->getOutputData('is_retire_date')) { ?>
                                <td><span style="color : #cd5c5c;"><?php echo $mgr->getOutputData('validenddate') ?></span></td>
<?php } else { ?>
                                <td><?php echo $mgr->getOutputData('validenddate') ?></td>
<?php } ?>
                              </tr>
                            </table></td>
                        </tr>
