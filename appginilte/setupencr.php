<link href="dist/css/monokai.css" rel="stylesheet" type="text/css">
<script src="dist/js/rainbow-custom.min.js"></script>
<?php $table = $_GET['table']; ?>
<blockquote>Please note: Once you install this on a table you will have to start with new data , existing data will not
	be properly rendered.</blockquote>
<h1>1. hooks/__global.php</h1>
<p>Place the code below inside the <b>hooks</b> folder in the file <b>__global.php</b>. You only have to do this once.
</p>
<button onclick="copyToClipBoard('code-block-global');" id="ctc">Copy To ClipBoard</button>
<pre><code data-language="php" id="code-block-global">
//Table Data Encryption function By AdminLTE Plugin For Appgini v 23.03.15
//If you change this encryption key here make sure you also change it in the file: appginilte_dec_td.php and the bulk encryption.decrption page if you set it up.
function encrypt_data($plain_text, $encryption_key='XG0CXT4vsXEykreMd8tcaN928u5ozKmd') {
	$iv = openssl_random_pseudo_bytes(16);
	$encrypted_text = openssl_encrypt($plain_text, 'aes-256-cbc', $encryption_key, 0, $iv);
	return base64_encode($encrypted_text . '::' . $iv);
}
//Table Data Encryption function By AdminLTE Plugin For Appgini
</code></pre>
<h1>2. hooks/
	<?php echo $table ?>.php
</h1>
<p>Place below code inside the <b>hooks</b> folder in the file <b>
		<?php echo $table ?>.php
	</b> inside the following functions respectively: <b>
		<?php echo $table ?>__before_insert
	</b> and <b>
		<?php echo $table ?>__before_update
	</b> </p>
	<button onclick="copyToClipBoard('code-block-t-hook');" id="ctc">Copy To ClipBoard</button>
<pre><code data-language="php" id="code-block-t-hook">
//Field Encryption Code By AdminLTE For Appgini Plugin v 23.03.15
  $currDir = dirname(__FILE__);
	$get_enc_file=file_get_contents(''.$currDir.'/table_encryption/<?php echo $table ?>-enc.json');
	$decode_file=json_decode($get_enc_file,true);
	$enc_fields=$decode_file['encryptedfields_<?php echo $table ?>'];
	$eachfield=explode(',', $enc_fields);
	$newdata=[];
	foreach($eachfield as $field){
		if (array_key_exists($field, $data)) {
			$encryptedvalue=encrypt_data($data[$field]);
			$newdata[$field]=$encryptedvalue;
		}
		
	}
	$data=array_merge( $data, $newdata);
//Field Encryption Code By AdminLTE For Appgini Plugin
</code></pre>
<h1>3. hooks/
	<?php echo $table ?>-tv.js
</h1>
<p>Place below code inside the <b>hooks</b> folder in the file <b>
		<?php echo $table ?>-tv.js
	</b> If the file doesnt exist you will have to create it manually first. </p>
	<button onclick="copyToClipBoard('code-block-tv-js');" id="ctc">Copy To ClipBoard</button>
<pre><code data-language="javascript" id="code-block-tv-js">
//Table view decryption code by AdminLTE plugin for Appgini v 23.03.15
jQuery(document).ready(() => { const table = document.getElementsByTagName("table")[0]; if (table) { var rows = table.getElementsByTagName("tr"); } for (const rowElement of rows) { const id = Number(rowElement.getAttribute("data-id")); if (id > 0) { getjsondata(id); } } function getjsondata(id) { const data = []; const xhr = new XMLHttpRequest(); xhr.open("GET", "hooks/table_encryption/<?php echo $table ?>-enc.json", true); xhr.onreadystatechange = () => { if (!(xhr.readyState === 4 && xhr.status === 200)) { return; } const jsonData = JSON.parse(xhr.responseText); const encryptedFields = jsonData.encryptedfields_<?php echo $table ?>; const explodedFields = encryptedFields.split(","); for (const explodedField of explodedFields) { const field_name_id = `<?php echo $table ?>-${explodedField}-${id}`; data.push(field_name_id); } decryptTD(data); }; xhr.send(); } async function decryptTD(data) { for (const dataElement of data) { var fnID = dataElement; const encField = jQuery(`#${fnID}`).text(); await $j.ajax({ type: "POST", url: "appginilte_dec_td.php", data: { encrypted_data: encField }, success(data) { const recID = fnID.match(/\d+$/)[0]; document.getElementById( fnID ).innerHTML = `<a onclick="document.myform.SelectedField.value = this.parentNode.cellIndex; document.myform.SelectedID.value = " ${recID}";="" document.myform.submit();="" return="" false;"="" href="<?php echo $table ?>_view.php?SelectedID=${recID}" style="display: block; padding:0px;">${data}</a>`; }, error(xhr, status, error) { console.error(xhr); }, }); } } });
//Table view decryption code by AdminLTE plugin for Appgini
</code></pre>
<h1>4. hooks/
	<?php echo $table ?>-dv.js
</h1>
<p>Place below code inside the <b>hooks</b> folder in the file <b>
		<?php echo $table ?>-dv.js
	</b> If the file doesnt exist you will have to create it manually first. </p>
<button onclick="copyToClipBoard('code-block-dv-js');" id="ctc">Copy To ClipBoard</button>
<pre><code data-language="javascript" id="code-block-dv-js">
//Detail view decryption code by AdminLTE plugin for Appgini v 23.03.15
const xhr = new XMLHttpRequest(); xhr.open("GET", "hooks/table_encryption/<?php echo $table ?>-enc.json", true); xhr.onreadystatechange = () => { if (!(xhr.readyState === 4 && xhr.status === 200)) { return; } const jsonData = JSON.parse(xhr.responseText); const encryptedFields = jsonData.encryptedfields_<?php echo $table ?>; const explodedFields = encryptedFields.split(","); for (const explodedField of explodedFields) { const fieldValue = document.getElementById(explodedField).value; sendData(explodedField,fieldValue); } }; xhr.send(); async function sendData(eachfield,fieldValue) { try { const response = await $j.ajax({ type: "POST", url: "appginilte_dec_td.php", data: { encrypted_data: fieldValue }, }); document.getElementById(eachfield).value = response; } catch (error) { console.error(error); } }
//Detail view decryption code by AdminLTE plugin for Appgini
</code></pre>
<style>
	#ctc {
		background-color: #4CAF50;
		/* Green */
		border: none;
		color: white;
		padding: 8px 16px;
		text-align: center;
		text-decoration: none;
		display: inline-block;
		font-size: 16px;
		margin: 4px 2px;
		cursor: pointer;
		border-radius: 4px;
	}

	#ctc:hover {
		background-color: #3e8e41;
		/* Dark green */
	}
</style>
<script>
	function copyToClipBoard(cB) {
		const codeBlock = document.getElementById(cB);
		// Select the code block's text
		const range = document.createRange();
		range.selectNode(codeBlock);
		window.getSelection().addRange(range);

		// Copy the selected text to the clipboard
		document.execCommand('copy');

		// Deselect the text
		window.getSelection().removeAllRanges();

		// Show a success message
		alert('Code copied to clipboard!');
	}
</script>