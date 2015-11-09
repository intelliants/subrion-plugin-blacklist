<form method="post" enctype="multipart/form-data" class="sap-form form-horizontal">
	{preventCsrf}

	<div class="wrap-list">
		<div class="wrap-group">
			<div class="wrap-group-heading">
				<h4>{lang key='general'}</h4>
			</div>

			<div class="row">
				<label class="col col-lg-2 control-label" for="email">{lang key='email'}</label>
				<div class="col col-lg-4">
					<input type="text" class="common" placeholder="mail@example.com" name="email" id="block_email" size="32" value="{if isset($smarty.post.email)}{$smarty.post.email}{/if}">
				</div>
			</div>
			<div class="row">
				<label class="col col-lg-2 control-label" for="input-title">{lang key='note'}</label>
				<div class="col col-lg-4">
					<textarea name="note">{if isset($smarty.post.note)}{$smarty.post.note}{/if}</textarea>
				</div>
			</div>
		</div>
		<div class="form-actions inline">
			<input type="submit" name="save" class="btn btn-primary" value="{if iaCore::ACTION_ADD == $pageAction}{lang key='add'}{/if}">
		</div>
	</div>
</form>