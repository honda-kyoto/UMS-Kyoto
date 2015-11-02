          <input type="hidden" name="list_no" id="list_no" value="<?php echo $mgr->getRequestData('list_no') ?>">
<?php include("view/users/detail_common_header.tpl") ?>
        <div id="hisTabs">
                  <ul class="hisclear">
                  <?php echo $mgr->getCardTabMenu() ?>
                  </ul>
                </div>
                <div id="hisBox">
<?php if ($mgr->getRequestData('list_no') == "nodata") { ?>
                   <table border="0" cellspacing="0" cellpadding="0" width="850">
                    <tr>
                      <td><h3>■カード情報</h3></td>
                    </tr>
                    <tr>
                      <td>※現在登録されている情報はありません。</td>
                    </tr>
                    <tr>
                      <td><img src="image/space.gif" alt="" width="1" height="15" /></td>
                    </tr>
                  </table>
<?php } else { ?>

        <h3>■カード種別</h3>
        <table border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td><input name="card_type<?php $mgr->getOutputData('card_type_disabled') ?>" type="radio" id="card_type_2" value="2" <?php echo $mgr->getCheckData('card_type', 2) ?> disabled /></td>
            <td><label for="card_type_2">ハンズフリータグ</label></td>
            <td>&nbsp;&nbsp;&nbsp;</td>
            <td><input type="radio" name="card_type<?php $mgr->getOutputData('card_type_disabled') ?>" id="card_type_1" value="1" <?php echo $mgr->getCheckData('card_type', 1) ?> disabled /></td>
            <td><label for="card_type_1">セキュリティーカード</label></td>
          </tr>
          <tr>
            <td colspan="5"><img src="image/space.gif" alt="" width="10" height="10" /></td>
          </tr>
        </table>
        <h3>■扉の権限</h3>
      <table border="0" cellspacing="3" cellpadding="3">
        <tr>
          <td valign="top"><table border="0" cellpadding="5" cellspacing="0" class="listTab">
            <tr>
              <th scope="col">記号</th>
              <th width="250" scope="col">棟名称</th>
              <th scope="col">権限</th>
            </tr>
            <tr>
              <td align="center" valign="middle" style="height: 40px; line-height: 92%;">8<br />
                13</td>
              <td>旧産科婦人科病棟</td>
              <td align="center"><input name="permission_1" type="checkbox" id="permission_1" value="1" <?php echo $mgr->getCheckData('permission_1', 1) ?> disabled /></td>
            </tr>
            <tr>
              <td align="center" valign="middle" style="height: 40px; line-height: 92%;">10<br />
                11</td>
              <td>外来診療棟<br />
                地階～４階、１階東</td>
              <td align="center"><input name="permission_2" type="checkbox" id="permission_2" value="1" <?php echo $mgr->getCheckData('permission_2', 1) ?> disabled /></td>
            </tr>
            <tr>
              <td align="center" valign="middle" style="height: 40px;">積0</td>
              <td>積貞棟地階～８階</td>
              <td align="center"><input name="permission_3" type="checkbox" id="permission_3" value="1" <?php echo $mgr->getCheckData('permission_3', 1) ?> disabled /></td>
            </tr>
            <tr>
              <td colspan="3" align="center" style="background-color: #fff;"><img src="image/space.gif" width="10" height="10" /></td>
              </tr>
            <tr>
              <td align="center" valign="middle" style="height: 34px;">積G</td>
              <td>積貞棟１階<br />
                （抗がん剤取揃室）</td>
              <td align="center"><input name="permission_4" type="checkbox" id="permission_4" value="1" <?php echo $mgr->getCheckData('permission_4', 1) ?> disabled /></td>
            </tr>
            <tr>
              <td align="center" valign="middle" style="height: 34px;">積K</td>
              <td>積貞棟地階<br />
                （給食部門）</td>
              <td align="center"><input name="permission_5" type="checkbox" id="permission_5" value="1" <?php echo $mgr->getCheckData('permission_5', 1) ?> disabled /></td>
            </tr>
            <tr>
              <td align="center" valign="middle" style="height: 34px;">積S</td>
              <td>積貞棟８階<br />
                （特別室SS）</td>
              <td align="center"><input name="permission_6" type="checkbox" id="permission_6" value="1" <?php echo $mgr->getCheckData('permission_6', 1) ?> disabled /></td>
            </tr>
            <tr>
              <td align="center" valign="middle" style="height: 34px;">積A</td>
              <td>積貞棟６階<br />
                （特別室SA）</td>
              <td align="center"><input name="permission_7" type="checkbox" id="permission_7" value="1" <?php echo $mgr->getCheckData('permission_7', 1) ?> disabled /></td>
            </tr>
            <tr>
              <td align="center" valign="middle" style="height: 34px;">北</td>
              <td>北病棟２階</td>
              <td align="center"><input name="permission_8" type="checkbox" id="permission_8" value="1" <?php echo $mgr->getCheckData('permission_8', 1) ?> disabled /></td>
            </tr>
            <tr>
              <td align="center" valign="middle" style="height: 34px;">M1</td>
              <td>外来診療棟地階<br />
                （北側：MEセンター）</td>
              <td align="center"><input name="permission_9" type="checkbox" id="permission_9" value="1" <?php echo $mgr->getCheckData('permission_9', 1) ?> disabled /></td>
            </tr>
            <tr>
              <td align="center" valign="middle" style="height: 34px;">M2</td>
              <td>外来診療棟地階<br />
                （南側：放射線部）</td>
              <td align="center"><input name="permission_10" type="checkbox" id="permission_10" value="1" <?php echo $mgr->getCheckData('permission_10', 1) ?> disabled /></td>
            </tr>
            <tr>
              <td align="center" valign="middle" style="height: 34px;">S1</td>
              <td>中央診療施設棟４階<br />
                （ハイブリッド手術室）</td>
              <td align="center"><input name="permission_11" type="checkbox" id="permission_11" value="1" <?php echo $mgr->getCheckData('permission_11', 1) ?> disabled /></td>
            </tr>
            <tr>
              <td align="center" valign="middle" style="height: 34px;">S2</td>
              <td>中央診療施設棟３階<br />
                （手術部）</td>
              <td align="center"><input name="permission_12" type="checkbox" id="permission_12" value="1" <?php echo $mgr->getCheckData('permission_12', 1) ?> disabled /></td>
            </tr>
            <tr>
              <td align="center" valign="middle" style="height: 34px;">S3</td>
              <td>中央診療施設棟４階<br />
                （麻酔科）</td>
              <td align="center"><input name="permission_13" type="checkbox" id="permission_13" value="1" <?php echo $mgr->getCheckData('permission_13', 1) ?> disabled /></td>
            </tr>
          </table></td>
          <td>&nbsp;</td>
          <td valign="top"><table border="0" cellpadding="5" cellspacing="0" class="listTab">
            <tr>
              <th scope="col">記号</th>
              <th width="250" scope="col">棟名称</th>
              <th scope="col">権限</th>
            </tr>
            <tr>
              <td align="center" valign="middle" style="height: 40px;">N1</td>
              <td>西病棟（中玄関のみ）</td>
              <td align="center"><input name="permission_14" type="checkbox" id="permission_14" value="1" <?php echo $mgr->getCheckData('permission_14', 1) ?> disabled /></td>
            </tr>
            <tr>
              <td align="center" valign="middle" style="height: 40px; line-height: 92%;">N2<br />
                N3<br />
                N4</td>
              <td>西病棟</td>
              <td align="center"><input name="permission_15" type="checkbox" id="permission_15" value="1" <?php echo $mgr->getCheckData('permission_15', 1) ?> disabled /></td>
            </tr>
            <tr>
              <td align="center" valign="middle" style="height: 40px; line-height: 92%;">R1<br />
                R2<br />
                R3</td>
              <td>和進会館２階
                （卒後臨床研修室）<br />
                ※研修医のみ申請可</td>
              <td align="center"><input name="permission_16" type="checkbox" id="permission_16" value="1" <?php echo $mgr->getCheckData('permission_16', 1) ?> disabled /></td>
            </tr>
            <tr>
              <td colspan="3" align="center" style="background-color: #fff;"><img src="image/space.gif" alt="" width="10" height="10" /></td>
              </tr>
            <tr>
              <td align="center" valign="middle" style="height: 34px;">Y1</td>
              <td>外来診療棟地階<br />
                （薬剤部パスボックス）</td>
              <td align="center"><input name="permission_17" type="checkbox" id="permission_17" value="1" <?php echo $mgr->getCheckData('permission_17', 1) ?> disabled /></td>
            </tr>
            <tr>
              <td align="center" valign="middle" style="height: 34px; line-height: 92%;">Y1<br />
                Y2<br />
                Y3<br />
                Y4</td>
              <td>外来診療棟地階<br />
                （薬剤部）</td>
              <td align="center"><input name="permission_18" type="checkbox" id="permission_18" value="1" <?php echo $mgr->getCheckData('permission_18', 1) ?> disabled /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"height: 34px;"">H1</td>
              <td>中央診療施設棟１階<br />
                （放射線部インフォメーション）</td>
              <td align="center"><input name="permission_19" type="checkbox" id="permission_19" value="1" <?php echo $mgr->getCheckData('permission_19', 1) ?> disabled /></td>
            </tr>
            <tr>
              <td align="center" valign="middle" style="height: 34px;">H2</td>
              <td>中央診療施設棟１階<br />
                （放射線部総合画像診断室）</td>
              <td align="center"><input name="permission_20" type="checkbox" id="permission_20" value="1" <?php echo $mgr->getCheckData('permission_20', 1) ?> disabled /></td>
            </tr>
            <tr>
              <td align="center" valign="middle" style="height: 34px;">J1<br />
                J2</td>
              <td>南病棟地階<br />
                （医療情報部電算機室）</td>
              <td align="center"><input name="permission_21" type="checkbox" id="permission_21" value="1" <?php echo $mgr->getCheckData('permission_21', 1) ?> disabled /></td>
            </tr>
            <tr>
              <td align="center" valign="middle" style="height: 34px;">J3</td>
              <td>南病棟地階<br />
                （医療情報部電算機室）</td>
              <td align="center"><input name="permission_22" type="checkbox" id="permission_22" value="1" <?php echo $mgr->getCheckData('permission_22', 1) ?> disabled /></td>
            </tr>
            <tr>
              <td align="center" valign="middle" style="height: 34px;">J4<br />
                J5</td>
              <td>外来診療棟地階<br />
                （病歴管理室）</td>
              <td align="center"><input name="permission_23" type="checkbox" id="permission_23" value="1" <?php echo $mgr->getCheckData('permission_23', 1) ?> disabled /></td>
            </tr>
            <tr>
              <td align="center" valign="middle" style="height: 34px;">T1</td>
              <td>臨床研究総合センター</td>
              <td align="center"><input name="permission_24" type="checkbox" id="permission_24" value="1" <?php echo $mgr->getCheckData('permission_24', 1) ?> disabled /></td>
            </tr>
            <tr>
              <td align="center" valign="middle" style="height: 34px;">D1</td>
              <td>第１臨床研究棟地階<br />
                （分子イメージング集中研究センター）</td>
              <td align="center"><input name="permission_25" type="checkbox" id="permission_25" value="1" <?php echo $mgr->getCheckData('permission_25', 1) ?> disabled /></td>
            </tr>
            <tr>
              <td colspan="2" align="left" valign="middle" style="height: 34px;">　再発行</td>
              <td align="center"><input name="reissue_flg" type="checkbox" id="reissue_flg" value="1" <?php echo $mgr->getCheckData('reissue_flg', 1) ?> disabled /></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td align="left"><img src="image/space.gif" alt="" width="1" height="5" /></td>
          <td align="left">&nbsp;</td>
          <td align="left">&nbsp;</td>
        </tr>
      </table>
        <h3>■所属情報等</h3>
      <table border="0" cellspacing="3" cellpadding="3">
        <tr>
          <td><table border="0" cellpadding="5" cellspacing="0" class="listTab">
            <tr>
              <th scope="col" width="94">所属情報１</th>
              <th scope="col" width="94">所属情報２</th>
              <th scope="col" width="94">所属情報３</th>
              <th scope="col" width="94">所属情報４</th>
              <th scope="col" width="94">キー番号</th>
              <th scope="col" width="94">UID</th>
            </tr>
            <tr>
              <td align="center"><?php echo $mgr->getOutputData('belong_info_1') ?></td>
              <td align="center"><?php echo $mgr->getOutputData('belong_info_2') ?></td>
              <td align="center"><?php echo $mgr->getOutputData('belong_info_3') ?></td>
              <td align="center"><?php echo $mgr->getOutputData('belong_info_4') ?></td>
              <td align="right"><?php echo $mgr->getOutputData('key_number') ?></td>
              <td align="right"><?php echo $mgr->getOutputData('uid') ?></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td align="left"><img src="image/space.gif" alt="" width="1" height="5" /></td>
        </tr>
        <tr>
          <td><table border="0" cellpadding="5" cellspacing="0" class="inputTab" width="100%">
            <tr>
              <th scope="col" width="94">利用期間</th>
              <td>
                <table border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td><?php echo $mgr->getOutputData('start_date') ?></td>
                    <td> 　～　 </td>
                    <td><?php echo $mgr->getOutputData('end_date') ?></td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <th scope="col">失効</th>
              <td><div class="CheckBoxTab">
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><input type="checkbox" name="suspend_flg" id="suspend_flg" value="1" <?php echo $mgr->getCheckData('suspend_flg', '1') ?> disabled /></td>
                      <td><label for="suspend_flg">一時的にカードの利用を停止</label></td>
                    </tr>
                  </table>
              </div></td>
            </tr>
            <tr>
              <th scope="col">削除</th>
              <td><div class="CheckBoxTab">
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><input type="checkbox" name="del_flg" id="del_flg" value="1" <?php echo $mgr->getCheckData('del_flg', '1') ?> disabled /></td>
                      <td><label for="del_flg">入退室管理システムからデータを削除</label></td>
                    </tr>
                  </table>
              </div></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td align="left"><img src="image/space.gif" alt="" width="1" height="5" /></td>
        </tr>
      </table>
<?php } ?>
      <table border="0" cellspacing="3" cellpadding="3">
        <tr>
          <td>
           <input type="button" value="　　一覧に戻る　　" onclick="returnList();" />
          </td>
        </tr>
        <tr>
          <td align="left"><img src="image/space.gif" alt="" width="1" height="5" /></td>
        </tr>
      </table>
      </div>