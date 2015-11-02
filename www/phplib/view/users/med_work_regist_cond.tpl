      <h3>■検索条件 (新規登録前に情報を確認します)</h3>
      <table border="0" cellpadding="2" cellspacing="0" class="searchTab">
        <tr>
          <th width="100">氏名(漢字・カナ)</th>
          <td><input name="kananame" type="text" id="kananame" size="20" value="<?php echo $mgr->getRequestData('kananame') ?>" /></td>
        </tr>
      </table>
      <div class="searchBlock">
        <a href="javascript:search();"  /><img src="image/btn_search.gif" title="検索" /></a>
        <a href="javascript:clearCond();" /><img src="image/btn_clear.gif" title="条件クリア" /></a>
      </div>
