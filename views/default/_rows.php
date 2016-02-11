<?php if (is_array($data)) foreach($data as $name=>$value): ?>
<tr data_id="<?php echo $name; ?>">
	<td><?php echo $name;                          ?></td>
	<td><?php echo $value['label'];                ?></td>
	<td><?php echo nl2br($value['description']);   ?></td>
	<td><?php // Вывод значения длиной не больше 50 символов
		$val = json_encode($value['value']);
		echo (iconv_strlen($val, 'utf-8') > 50) ? iconv_substr($val, 0, 50, 'utf-8') : $val; 
	?></td>
</tr>
<?php endforeach; ?>