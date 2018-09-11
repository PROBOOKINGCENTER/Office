<style type="text/css">
	
	
	.productList{
		min-height: calc(100vh - 50px);
	    -moz-border-radius: 2px;
	    -webkit-border-radius: 2px;
	    border-radius: 2px;
	    background-color: #ffff;
	    box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 1px 6px rgba(0,0,0,0.12);
	    padding: 2px;
	}
	.productList__header{
	    /*background-color: #fff;
	    border-radius: 0 0 6px 6px;
	    box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 1px 6px rgba(0,0,0,0.12);
	    padding: 12px 20px;*/
	    /*margin: 2px;*/
	    padding: 11px 20px;
    	background-color: #f1f1f1;

	    /*border-bottom: 1px solid #ccc;*/

	    /*-webkit-transition: opacity .25s cubic-bezier(0.4,0.0,0.2,1),visibility 0s linear 0s;*/
	    /*transition: opacity .25s cubic-bezier(0.4,0.0,0.2,1),visibility 0s linear 0s;*/
	}
	.productList__alert{
		text-align: center;
	}
	.productList__alert>div{
		margin: 40px;
		color: #999;
		display: none;
	}

	.productList__content.has-loading .productList__alert .productList__loading,
	.productList__content.has-error .productList__alert .productList__error,
	.productList__content.has-more .productList__alert .productList__more,
	.productList__content.has-empty .productList__alert .productList__empty
	{
		display: block;
	}
	.productList__alert .loader-spin-wrap{
		margin: 4px auto;
	}


	.ui-filter{
		/*display: flex;*/
		display: table;
		width: 100%;
	}
	.ui-filter-item{
		/*margin-left: 6px;*/
		/*margin-right: 6px;*/
		display: table-cell;
		
	}
	.ui-filter-item + .ui-filter-item{
		padding-left: 6px
	}
	.ui-filter .label{
		display: block;
		font-size: 11px;
		font-weight: bold;
		margin-bottom: 2px;
		color: #999;
		line-height: 1
	}

	
	.ui-filter .ui-filter-item.daterange .daterange-clear{
		bottom: 14px;
	}

	.productList__scrolltop{
		position: fixed;
	}

	.productList__filter .input{
		height: 48px;
		line-height: 48px;
		border-radius: 0;
		width: 100%;
	}


</style>

<div id="DataTable" class="productList" data-plugin="productList"  data-options="<?=Fn::stringify( $this->listOpt )?>">
	
	<div role="toolbar">
		<header class="productList__header" role="header">
			
				<div role="filter">
					<div class="productList__filter ui-filter">

						<div class="ui-filter-item">
							<label class="label">ค้นหา</label>
							<form class="form-search" action="#" data-filter="search">
								<input class="input inputtext search-input" type="text" id="search-query" placeholder="ค้นหา..." name="q" autocomplete="off">
							</form>
						</div>

						<div class="ui-filter-item" style="width: 180px">
							<label class="label" id="airline">สายการบิน</label>
							<select class="input inputtext" id="airline" name="airline" data-filter="selector">
								<option value="">-- ทั้งหมด --</option>
								<?php 
									foreach ($this->airlineList as $key => $value) {
										echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
									}
								?>
							</select>
						</div>

						<div class="ui-filter-item" style="width: 180px">
							<label class="label" for="country">ประเทศ</label>
							<select class="input inputtext" id="country" name="country" data-filter="selector">
								<option value="">-- ทั้งหมด --</option>
								<?php 
									foreach ($this->countryList as $key => $value) {
										echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
									}
								?>
							</select>
						</div>

						<div class="ui-filter-item city" style="width: 180px">
							<label class="label" id="city">เมือง</label>
							<select class="input inputtext" id="city" name="city" data-filter="selector">
								<option value="">-- ทั้งหมด --</option>
							</select>
						</div>

						<div class="ui-filter-item" style="width: 180px">
							<label class="label" for="ser">ซีรี่ย์ทัวร์</label>
							<select class="input inputtext" id="ser" name="ser" data-filter="selector">
								<option value="">-- ทั้งหมด --</option>

							</select>
						</div>

						<div class="ui-filter-item daterange" style="width:240px">
							<label class="label">ช่วงเวลาเดินทาง</label>
							<input class="input inputtext daterange-input" type="text" name="date" data-filter="daterange">
							<button class="daterange-clear" type="button" data-action="cleardate"><i class="icon-remove"></i></button>
						</div>

						<!-- <div class="ui-filter-item"style="width: 140px">
							<label class="label">&nbsp;</label>
							<button type="button" class="input btn btn-blue" data-action="submit" >ค้นหา</button>
						</div> -->

						<div class="ui-filter-item" style="width: 48px">
							<label class="label">&nbsp;</label>
							<button data-filter="refresh" type="button" class="input btn"><i class="icon-refresh"></i></button>
						</div>
					</div>

					<!-- <div class="productList__filter ui-filter mts">
						<div class="ui-filter-item">
							<label class="checkbox"><input type="checkbox" name=""><span>โปรดันขาย</span></label>
						</div>
					</div> -->
				</div>

		</header>
	</div>

	<div class="productList__content pal" role="content">
		<table class="table-tour" role="listsbox"></table>

		<div class="productList__alert">
			<div class="productList__loading"><div class="loader-spin-wrap"><div class="loader-spin"></div></div><p>Loading...</p></div>
			<div class="productList__empty">
				<div class="empty-icon"><i class="icon-flag"></i></div>
	        	<div class="empty-title">No Results Found.</div>
			</div>
			<div class="productList__error">Don't connected, <a type="button" data-action="tryagain">Try again</a></div>
			<div class="productList__more"><a type="button" class="btn btn-large" data-action="more">More</a></div>
		</div>
	</div>


	<div class="productList__scrolltop u-scrolltop" data-action="scrolltop"><i class="icon-arrow-up"></i></div>

</div>