<?php $this->load_view('common/header', array('title' => 'Simple Todo', 'alt' => 'Todo Lists')); ?>

<div id="selectList">
	<p>
		<label for="lists">My Lists</label>
		<select id="lists" name="lists">
			<option value="">Select list...</option>
			<?php if(!is_null($lists)): ?>
				<?php foreach($lists as $list): ?>
					<option value="<?=$list->id?>"><?=$list->name?></option>
				<?php endforeach; ?>
			<?php endif;?>
		</select>

		<label><a href="#" id="createListLink">Create</a> a new list.</label>
	</p>
</div>


<div id="createList" style="display:none;">
	<p>
		<label for="newlist">List name</label>
		<input type="text" id="newlist" name="newlist" value="" />
	</p>
</div>


<div id="listview" style="display:none;">
	<h4 id="listname">Current List</h4>

	<label for="newitem">Add an item to this list</label>
	<input type="text" id="newitem" name="newitem" value="" />

	<table id="listitems">
		<thead>
			<td width="94%"></td>
			<td></td>
		</thead>
		<tbody>

		</tbody>
	</table>

</div>


<script type="text/javascript">

	$.extend({
		postJSON: function (uri, data, callback) {
			return $.post(uri, data, callback, 'json');
		}
	});



	$('#lists').change(function() {
		
		$('#listitems > tbody').empty();

		if(!!$(this).val()) // if has value
		{
			$.getJSON('/list/items/' + $(this).val(), function(data) {
				if(data.length) {
					var src = '';
					$.each(data, function(i) {
						src += '<tr id="r' + data[i].id + '"><td>' + data[i].text + '</td>';
						src += '<td><a href="#"><img src="/public/images/check.png" class="donezo" tid="' + data[i].id + '" /></a></tr>';
					});
					$('#listitems > tbody').append(src);

				}
			});
			$('#listname').text($('#lists option:selected').text());
			$('#listview').show();
		}
		else
		{
			$('#listview').hide();
		}
		
	});


	$('#createListLink').live('click', function(e) {
		
		$('#selectList').slideUp(500);
		$('#createList').slideDown(500);

		$('#newlist').live('keyup', function(e) {
			if(e.which == 13 && $(this).val().length > 0) {
				var name = $(this).val();

				$.postJSON('/list/create', { name: name }, function(data) {
					if(data.success) {
						$('#lists').append($('<option>', { value: data.id, text: name })).val(data.id).change();
						$('#newlist').val('');
					}
					$('#createList').slideUp(500);
					$('#selectList').slideDown(500);
				});
			} else if(e.keyCode == 27) {
				$('#createList').slideUp(500);
				$('#selectList').slideDown(500);
			}

		});

	});


	$('#newitem').live('keypress', function(e) {
		if(e.which == 13 && $(this).val().length > 0) {
			var title = $(this).val();
			$.postJSON('/list/add', { list_id: $('#lists').val(), text: title }, function(data) {
				if(data.success) {
					var src = '';
					src += '<tr id="r' + data.id + '"><td>' + title + '</td>';
					src += '<td><a href="#"><img src="/public/images/check.png" class="donezo" tid="' + data.id + '" /></a></tr>';

					$('#listitems > tbody').append(src);

					$('#newitem').val('');
				}
			});
		}
	});


	$('.donezo').live('click', function(e) {
		var id = $(this).attr('tid');

		$('#r' + id).fadeOut(500, function() {
			$('#r' + id).remove();
		});

		$.getJSON('/list/checked/' + id, function(data) {
			if(data.success) {
				// moved up to fade out instantly
			}
		});
	});

</script>

<?php $this->load_view('common/footer'); ?>