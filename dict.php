<?php echo '<' . '?xml version="1.0" encoding="utf-8"?' . '>'; ?>
<?php
require 'func.php';

//変化型テーブル読み込み
$fname = 'affixTable.csv';
$affixTable = new SplFileObject($fname);
$affixTable -> setFlags(SplFileObject::READ_CSV); //[0]対象品詞、[1]形態、[2]説明のcsv

//json読み込み
$fname = 'idyer.json';
$json = file_get_contents($fname);
$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
$json = json_decode($json,true);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja" xml:lang="ja" dir="ltr">
<head>
<?php
	require 'anal.php';
?>
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Content-Style-Type" content="text/css" /> 
<meta name="Description" content="イジェール語オンライン辞書" />
<meta name="keywords" content="人工言語,辞書," />
<link rel="stylesheet" type="text/css" href="dict.css" />
<link rel="shortcut icon" href="favicon.ico" />
<link rel="icon" href="favicon.ico" />
<title>イジェール語 オンライン辞書</title>
</head>
<body>
<div class="all">
	<div id="header">
	
	<h1>イジェール語 オンライン辞書</h1>
	<ul id="menu">
		<li><a class="menu" href="https://zaslon.info/idyerin/%e8%be%9e%e6%9b%b8%e5%87%a1%e4%be%8b/">凡例</a></li>
		<li><a class="menu" href="https://zaslon.info/idyer">ホームへ戻る</a></li>
	</ul>
	<div class="dictVer">
		<?php
		date_default_timezone_set('Asia/Tokyo');
		echo "<p>プログラム更新日：".date("Y/m/d",filemtime(__FILE__))."</p>";
		echo "<p>辞書更新日：".date("Y/m/d",filemtime($fname))."<br />";
		echo "単語数：".count($json["words"])."</p>";
		?>
	</div>
	<?php
	$checked_1 = "";
	$checked_2 = "";
	$checked_3 = "";
	$checked_4 = "";
	$checked_5 = "";
	$checked_6 = "";
	$checked_7 = "";
	$checked_8 = "";
	
	//スーパーグローバル関数の処理。
	//返り値：
	//文字列 or false
	
	$type = ((isset($_GET["type"])) && ($_GET["type"] !== "")) ? $_GET["type"] :false;
	$mode = ((isset($_GET["mode"])) && ($_GET["mode"] !== "")) ? $_GET["mode"] :false;
	$idf = ((isset($_GET["Idf"])) && ($_GET["Idf"] !== "")) ? true  :false;
	$keyBox = ((isset($_GET["keyBox"])) && ($_GET["keyBox"] !== "")) ? $_GET["keyBox"]  :false;
	$id = (isset($_GET["id"])) && ($_GET["id"] !== "") ? (int)$_GET["id"] :false;
	$page = ((isset($_GET["page"])) && ($_GET["page"] !== "") && (preg_match("/^[0-9]+$/", $_GET["page"]))) ? (int)$_GET["page"] : 1; //ページIDに数字以外を入力された場合、強制的に1とする。
	
	if($type) {
		switch($type) {
			case "word":
				$checked_1 = "checked";
				break;
			case "trans":
				$checked_2 = "checked";
				break;
			case "both":
				$checked_3 = "checked";
				break;
			case "all":
				$checked_4 = "checked";
				break;
			default:
				$checked_3 = "checked";
				$type = "both";
				break;
		}
	}else{
		//デフォルトで両方検索を選択
		$checked_3 = "checked";
		$type = "both";
	}
	
	if($idf) {
		$checked_5 = "checked";
	}else{
		//デフォルトで空欄
	}
	
	if($mode) {
		switch($mode) {
			case "prt":
				$checked_6 = "checked";
				break;
			case "fwd":
				// $checked_7 = "checked"; 本来はこの表記だが、前方一致モードで検索された次の検索時は部分一致を選択するようにする
				$checked_6 = "checked";
				break;
			case "perf":
				$checked_8 = "checked";
				break;
			default:
				$checked_6 = "checked";
				$mode = "prt";
				break;
		}
	}else{
		//デフォルトで部分一致を選択
		$checked_6 = "checked";
		$mode = "prt";
	}
	?>
	
	<form action="" method="GET">
		<div class='textAndSubmit'><input type="text" name="keyBox"><input type="submit" name="submit" value="検索"></div>
<!--		<div class='buttonAndLabel'><input type="radio" name="type" id="c1" value="word" <?php echo $checked_1; ?>><label for="c1">見出し語検索</label></div> -->
<!--		<div class='buttonAndLabel'><input type="radio" name="type" id="c2" value="trans" <?php echo $checked_2; ?>><label for="c2">訳語検索</label></div> -->
		<div class='buttonAndLabel'><input type="radio" name="type" id="c3" value="both" <?php echo $checked_3; ?>><label for="c3">見出し語・訳語検索</label></div>
		<div class='buttonAndLabel'><input type="radio" name="type" id="c4" value="all" <?php echo $checked_4; ?>><label for="c4">全文検索</label></div>
		<div class='buttonAndLabel'><input type="checkbox" name="Idf" id="c5" value="true" <?php echo $checked_5; ?>><label for="c5">イジェール文字表示</label></div>
		<div class='buttonAndLabel'><input type="radio" name="mode" id="c6" value="prt" <?php echo $checked_6; ?>><label for="c6">部分一致</label></div>
<!--		<div class='buttonAndLabel'><input type="radio" name="mode" id="c7" value="fwd" <?php echo $checked_7; ?>><label for="c7">前方一致</label></div> -->
		<div class='buttonAndLabel'><input type="radio" name="mode" id="c8" value="perf" <?php echo $checked_8; ?>><label for="c8">完全一致</label></div>
		<input type="hidden" name="page" value="1">
	</form>
	</div>

	<div id="main">
	<?php
	$func = $mode ? setFunc($mode): "stripos";
	$hitWordIds = array();
	$hitEntryIds = array();
	$hitAmount =0;
	$keyWords = "";
	$totalPages = 0;
	$wordNumPerPage = 40;
	//keyBoxに入力されているときのみ，$keyWordsに代入
	if ($keyBox){
	//数字が一部にでも含まれていたら$keyWordsは空になる．
		if (preg_match("/^.*[0-9].*/", $keyBox)) {
			echo "<p>検索ワードに数字を入力しないでください。数字を検索する場合は漢数字で入力してください。</p>";
		} else {
			$keyWords = preg_replace('/[　]/u', ' ', $_GET["keyBox"]);//全角スペースを半角スペースに変換
			$keyWords = preg_replace('/\s\s+/u', ' ', $keyWords);//スペース2つ以上であれば，1つに削減
			$keyWords = deleteNonIdyerinCharacters($keyWords);
			$keyWords = explode(' ',$keyWords);//スペースで区切られた検索語を分離して配列に格納
		}
	}

	//ここから検索部。検索の結果を格納する。
	if(empty($keyWords[0])){
		echo "<p>検索ワードを入力してください。</p>";//$keyWordsが空なら警告を表示して終了する．
    }else{
    	//全てに優先してid指定時の表示を行う。
		if($id) {
			$hitWordIds[] = $id;
			foreach ($json["words"] as $entryId => $singleEntry){
				if ($singleEntry["entry"]["id"] === $id){
					$hitEntryIds[]= $entryId;
					break 1;
				}
			}
		}else{
			//ここに検索して、内容をarrayに格納する処理を入れる。
			foreach ($json["words"] as $entryId =>$singleEntry){
				$wordId = $singleEntry["entry"]["id"];
				$singleEntry["entry"]["form"] = deleteNonIdyerinCharacters($singleEntry["entry"]["form"]);
				$wordForm = $singleEntry["entry"]["form"];
				
				////////////////ここから接辞サジェスト機能
				$wordFormForPreffixs = array();
				$texts = array();
				
				//動詞の場合、接尾辞はeを外した形を語幹としているので、それにあわせる。
				if (mb_stripos($singleEntry["translations"][0]["title"],"動詞") !== false) {
					$wordFormForSuffix = substr($wordForm, 0, strlen($wordForm)-1);
				}else{
					$wordFormForSuffix = $wordForm;
				}
				//記述詞の場合、末尾の(i)nを外した形に対しての派生があるので、それをチェックする。
				if (mb_stripos($singleEntry["translations"][0]["title"],"記述詞") !== false) {
					if (endsWith($wordForm, 'in')){
						$wordFormForPreffixs[1] = substr($wordForm, 0, strlen($wordForm)-2);
					}
					$wordFormForPreffixs[0] = substr($wordForm, 0, strlen($wordForm)-1);
				}else{
					$wordFormForPreffixs[0] = $wordForm;
				}
				
				//辞書のデータに対して接辞テーブルとの該当を調べる
				foreach ($affixTable as $singleAffix){
					
					$singleAffixWithoutBracket = preg_replace('/\(.*?\)/u', '', $singleAffix[1]); //カッコつき接辞のカッコ内をカッコごとなくした形
					if (preg_match('/(?<=\().*?(?=\))/u',$singleAffix[1]) === 1) {
						preg_match('/(?<=\().*?(?=\))/u',$singleAffix[1], $singleAffixCharBetweenBracket);
						$singleAffixCharBetweenBracket = $singleAffixCharBetweenBracket[0]; //カッコつき接辞のカッコ内を取り出した文字列
					}else{
						$singleAffixCharBetweenBracket = '';
					} 
					$singleAffixWithBracket = preg_replace('/[\(\)]/u', '', $singleAffix[1]); //カッコつき接辞のカッコを外した形
					
					if (startsWith($singleAffix[1], "-")) { //接尾辞
						if (endsWithVowel($wordForm)){//母音で終わる単語の場合
							$texts[0] = $wordFormForSuffix . substr($singleAffixWithoutBracket, 1);
						}else{
							$texts[0] = $wordFormForSuffix . substr($singleAffixWithBracket, 1);
						}
					}elseif (endsWith($singleAffix[1], "-")){ //接頭辞
						foreach ($wordFormForPreffixs as $index => $singleWordFormForPreffix){
							if (startsWithVowel($wordForm)){//母音で始まる単語の場合
								$texts[$index] = substr($singleAffixWithoutBracket, 0, strlen($singleAffixWithoutBracket)-1) . initialVoicing($singleWordFormForPreffix);
							}else{
								$texts[$index] = substr($singleAffixWithBracket, 0, strlen($singleAffixWithBracket)-1) . initialVoicing($singleWordFormForPreffix);
							}
						}
					}elseif (stripos($singleAffix[1], "-") !== false){
						//接周辞：今の所存在しない
					}
					foreach ($texts as $singleText) {
						if ($keyWords[0] === $singleText && mb_stripos($singleEntry["translations"][0]["title"], $singleAffix[0])!== false){
							echo '<p class="suggest">もしかして、';
							echo makeLinkStarter($wordForm, $_GET["type"], $_GET["mode"],1,$wordId) . $wordForm . '</a><span class=wordId>#' . $wordId . '</span>';
							echo 'の '. $singleAffix[2] . ' ? </p>';
						}
					}
				}
				/////////ここまで接辞サジェスト機能
				
				//検索部
				foreach ($keyWords as $eachKey){
					if(isHit($singleEntry, $eachKey, $type, $mode)) {
						$hitWordIds[] = $wordId;
						$hitEntryIds[]= $entryId;
					}
				}
			}
		}
		
		//ここから表示部
		$hitAmount = count($hitWordIds);
		echo('<p class="result">');		
		$i = $wordNumPerPage*($page-1);
		if($hitAmount === 0){
			echo_h($_GET["keyBox"].' での検索結果：0件');
		}else{
			echo_h($_GET["keyBox"].' での検索結果：'.$hitAmount."件(".($i+1)."から".min($i+$wordNumPerPage,$hitAmount)."件目)");
		}
		echo("</p>");
	
		while ( $i < ($wordNumPerPage*$page) && $i < $hitAmount) {
		//ここに検索結果の繰り返し表示を入れる。
			echo '<ul class="wordEntry">';
			if($idf) {
				echo '<li class="wordForm"><span class="idyerin">' . $json["words"][$hitEntryIds[$i]]["entry"]["form"] . '</span>';
			}else{
				echo '<li class="wordForm">' . $json["words"][$hitEntryIds[$i]]["entry"]["form"];
			}
			echo '<span class="wordId">#'. $hitWordIds[$i] . '</span></li>';
			foreach ($json["words"][$hitEntryIds[$i]]["translations"] as $singleTranslation){
				echo '<li><span class="wordTitle">' . $singleTranslation["title"] . '</span>';
				foreach ($singleTranslation["forms"] as $singleTranslationForm){
					echo $singleTranslationForm;
					if ($singleTranslationForm !== end($singleTranslation["forms"])){
						//最後のとき以外に「、」を追加
						echo '、';
					}
				}
				echo '</li>';
			}
			foreach ($json["words"][$hitEntryIds[$i]]["contents"] as $singleContent){
				echo '<li class="wordContents">';
				echo '<span class="wordContentTitle">' . $singleContent["title"] . '</span>';
				if ($singleContent["title"] !== "語源"){
				    echo $singleContent["text"];
				}else{
					$text = '';
					$isNextLink = true;
					$singleContent["text"] = preg_split ('/([:\/*>+|])/u', $singleContent["text"], -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
					foreach ($singleContent["text"] as $index => $singleContentText){
						if ($isNextLink === false){
							$isLink = false;
							$isNextLink = true;
						}else{
				    		$isLink = true;
				    	}
						//「.」を文字列に含むとき
						if (stripos($singleContentText, '.') !== false){
							$isLink = false;
						//文字列が日本語を含むとき
						}elseif (isDoublebyte($singleContentText)){
							$isLink = false;
						//文字列がデリミタで、次に影響を及ぼさないもののとき
						}elseif (preg_match ('/[:\/>+]/u', $singleContentText) === 1){
							$isLink = false;
						//文字列がデリミタで、次に影響を及ぼすもののとき
						}elseif (preg_match ('/[*|]/u', $singleContentText) === 1){
							$isLink = false;
							$isNextLink = false;
						//右端以外のとき、ひとつ右を見る
						}elseif ($index+1 < count($singleContent["text"])){
							if (preg_match ('/[:\/]/u', $singleContent["text"][$index+1]) === 1){ 
								$isLink = false;
							}
						}
						//表示生成部
						if ($isLink){
							makeLinkStarter($singleContentText,'both', 'fwd', 1);
							echo $singleContentText . '</a>';
						}else{
							$isLink = true;
							echo $singleContentText;
						}
					}
				}
				echo '</li>';
			}

			$relationTitles = array();
			foreach ($json["words"][$hitEntryIds[$i]]["relations"] as $singleRelation){
				if (array_search($singleRelation["title"],$relationTitles) === false){
					echo '<li class="wordRelation"><span class="wordRelation">' . $singleRelation["title"] . '</span>';
					$relationTitles[] = $singleRelation["title"];
				}
				$conForm =  str_replace(" ", "+", $singleRelation["entry"]["form"]);//リンク作成のため，スペースを全て+で接続した形に変換
				makeLinkStarter($conForm,$_GET["type"], $_GET["mode"],1,$singleRelation["entry"]["id"]);
				echo $singleRelation["entry"]["form"] . '</a><span class="wordId">#' . $singleRelation["entry"]["id"] . '</span>';
//				if ($singleRelation !== end($json["words"][$hitEntryIds[$i]]["relations"])){
//					//最後のとき以外に「, 」を追加
//					echo ', ';
//				}
			}
			echo '</li>';
			echo '</ul>';
			$i++;
		}
	}


	//ページ送り機能

	echo('<ul class="navigation">');
	if ($wordNumPerPage<$hitAmount) {
		$totalPages = ceil($hitAmount/$wordNumPerPage);
		$i = 1;
		$conWord =  implode ("+", $keyWords);//リンク作成のため，スペースを全て+で接続した形に変換
		while ($i <= $totalPages) {
			echo '<li';
			if ($page === $i){
				echo ' class=currentPage';
			}
			echo '>';
			if ($page !== $i){
				makeLinkStarter($conWord, $type, $mode, $i);
				echo_h($i);
				echo '</a>';
			}else{
				echo_h($i);
			}
			echo '</li>';
			$i++;
		}
	}else{
	}
	echo('</ul>');
	?>
	
	</div>
	<div id="footer">
		<p>&copy; 2010-<?php echo date('Y'); ?> Zaslon</p>
	</div>
</div>
</body>
</html>