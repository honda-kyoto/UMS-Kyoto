      <h3>■検索条件</h3>
      <table border="0" cellpadding="2" cellspacing="0" class="searchTab">
        <tr>
          <th>統合ID</th>
          <td><input name="login_id" type="text" id="login_id" size="20" value="<?php echo $mgr->getRequestData('login_id') ?>"  /></td>
          <th width="80">カードNo.</th>
          <td><input name="staffcode" type="text" id="staffcode" size="20" value="<?php echo $mgr->getRequestData('staffcode') ?>" /></td>
          <th width="100">氏名(漢字・カナ)</th>
          <td><input name="kananame" type="text" id="kananame" size="20" value="<?php echo $mgr->getRequestData('kananame') ?>" /></td>
        </tr>
        <tr>
          <th>職種</th>
          <td><select name="job_id" id="job_id" style="width:120px;">
              <option value="">----</option>
              <?php echo $mgr->getSelectList('job_id') ?>
            </select></td>
          <th>役職</th>
          <td><select name="post_id" id="post_id" style="width:120px;">
              <option value="">----</option>
              <?php echo $mgr->getSelectList('post_id') ?>
            </select></td>
          <th nowrap>常勤／非常勤</th>
          <td><select name="joukin_kbn" id="joukin_kbn" style="width:120px;">
              <option value="">--</option>
              <?php echo $mgr->getSelectList('joukin_kbn') ?>
            </select></td>
        </tr>
        <tr>
          <th width="80">所属</th>
          <td colspan="5"><select name="belong_class_id" id="belong_class_id" onchange="belongClassChange(this.value);" style="width:120px;">
              <option value="">---大分類---</option>
              <?php echo $mgr->getSelectList('belong_class_id') ?>
            </select><div class="floatLeft" id="belongClassJs"></div>
            <select name="belong_div_id" id="belong_div_id" onchange="belongDivChange(this.value);" style="width:120px;">
              <option value="">---部門---</option>
              <?php echo $mgr->getSelectList('belong_div_id', "", $mgr->getRequestData('belong_class_id')) ?>
            </select><div class="floatLeft" id="belongDivJs"></div>
            <select name="belong_dep_id" id="belong_dep_id" onchange="belongDepChange(this.value);" style="width:120px;">
              <option value="">---部---</option>
              <?php echo $mgr->getSelectList('belong_dep_id', "", $mgr->getRequestData('belong_div_id')) ?>
            </select><div class="floatLeft" id="belongDepJs"></div>
            <select name="belong_sec_id" id="belong_sec_id" onchange="belongSecChange(this.value);" style="width:120px;">
              <option value="">---課・科---</option>
              <?php echo $mgr->getSelectList('belong_sec_id', "", $mgr->getRequestData('belong_dep_id')) ?>
            </select><div class="floatLeft" id="belongSecJs"></div>
            <select name="belong_chg_id" id="belong_chg_id" style="width:120px;">
              <option value="">---係・室・他---</option>
              <?php echo $mgr->getSelectList('belong_chg_id', "", $mgr->getRequestData('belong_sec_id')) ?>
            </select></td>
        </tr>
        <tr>
          <th width="80">オプション</th>
          <td colspan="5">
            <div class="CheckBoxTab">
              <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td><input type="radio" name="search_option" id="search_option_0" value="0" <?php echo $mgr->getCheckData('search_option', '0') ?> /></td>
                  <td><label for="search_option_0">全ての利用者を表示</label></td>
                  <td>&nbsp;</td>
                  <td><input type="radio" name="search_option" id="search_option_1" value="1" <?php echo $mgr->getCheckData('search_option', '1') ?> /></td>
                  <td><label for="search_option_1">退職者を表示しない</label></td>
                  <td>&nbsp;</td>
                  <td><input type="radio" name="search_option" id="search_option_2" value="2" <?php echo $mgr->getCheckData('search_option', '2') ?> /></td>
                  <td><label for="search_option_2">利用期間外の利用者を表示しない</label></td>
                </tr>
            </table>
            </div>
          </td>
        </tr>
      </table>
      <div class="searchBlock">
        <a href="javascript:search();"  /><img src="image/btn_search.gif" title="検索" /></a>
        <a href="javascript:clearCond();" /><img src="image/btn_clear.gif" title="条件クリア" /></a>
      </div>
