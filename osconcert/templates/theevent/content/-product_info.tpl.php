{{SECTION_NO_PRODUCT_START}}
<div>{{VALUE_PRODUCT_NOT_FOUND}}</div>
{{SECTION_NO_PRODUCT_END}}
{{SECTION_PRODUCT_START}}
{{VALUE_PRODUCT_FORM}}
<div id="template">
<section id="performance">
    <div class="container-fluid">
		<div class="section-header">
			<h2>{{VALUE_HEAD_NAME}}</h2>
		</div>
		<div class="row">
			<div class="col-lg-9 content">
				<h4>{{VALUE_CATEGORIES_VENUE}}{{VALUE_SPACER}}{{VALUE_CATEGORIES_DATE}}{{VALUE_SPACER}}{{VALUE_CATEGORIES_TIME}}</h4>{{VALUE_CATEGORIES_DESC}}{{VALUE_GA_TOTAL}}
		    </div>
			<div class="col-lg-3 performance-img">
				{{SECTION_IMAGE_START}}
	  {{VALUE_FIRST_IMAGE}}<br><span id="titleContainer">{{VALUE_FIRST_IMAGE_TITLE}}</span>

{{SECTION_IMAGE_END}}
			</div>
        </div>
     </div>
	<div class="row mt-3">
    <div class="col-sm-9  mb-sm-0">
              <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-sm-between mb-4">
                <ul class="list-inline mb-2 mb-sm-0">
                  <li class="list-inline-item h4 font-weight-light mb-0">{{VALUE_PRODUCTS_PRICE}}</li>
                </ul>
              </div>
			  <h3>{{VALUE_PRODUCTS_NAME}} {{VALUE_GA_QTY}}</h3>
             <p class="mb-4 text-muted">{{VALUE_PRODUCTS_DESCRIPTION}} </p>
            <div class="row">
				<div class="col-12">
					{{SECTION_PRODUCTS_SALEMAKER_START}}
					<strong>{{VALUE_SELECT_SALEMAKER_DISCOUNT}}</strong>
					<div>
					{{REPEAT_PRODUCTS_SALEMAKER_START}}
					<div>{{REPEAT_PRODUCTS_SALEMAKER_OPTION}}{{REPEAT_PRODUCTS_SALEMAKER_OPTION_TEXT}}</div>
					{{REPEAT_PRODUCTS_SALEMAKER_END}}
					</div>
					<div class="saleMakerInfo" style="display:none" id="salemaker_info"></div>
					{{SECTION_PRODUCTS_SALEMAKER_END}}
					{{SECTION_PRODUCTS_PRICE_BREAK_START}}
					<strong>{{VALUE_PRODUCTS_PRICE_BREAK_HEADING}}</strong>
					<div>
						{{REPEAT_PRODUCTS_PRICE_BREAK_START}}
						<div>{{VALUE_PRODUCTS_PRICE_BREAK_BUY}}</div>
						<div>{{REPEAT_PRODUCTS_PRICE_BREAK_QUANTITY}}</div>
						<div>{{VALUE_PRODUCTS_PRICE_BREAK_SAVE}}</div>
						<div>{{REPEAT_PRODUCTS_PRICE_BREAK_PRICE}}</div>
						<div>{{VALUE_PRODUCTS_PRICE_BREAK_EACH}}</div>
						{{REPEAT_PRODUCTS_PRICE_BREAK_END}}
					</div>
						{{SECTION_PRODUCTS_PRICE_BREAK_END}}
				</div>
					<div class="col-md-12"><label class="col-12 col-form-label">{{VALUE_TEXT_PRODUCTS_QUANTITY}}</label>
					</div>
					<div class="col-12 col-lg-8 detail-option mb-5" style="border: 0px blue solid">
					<table id="gaq"><tr>
					<th>{{VALUE_ADD_QUANTITY_MINUS}}</th>
					<th>{{VALUE_LEFT_SYMBOL}}</th>
					<th class="pull-left">{{VALUE_PRODUCTS_QUANTITY}}</th>
					<th>{{VALUE_RIGHT_SYMBOL}}</th>
					<th>{{VALUE_ADD_QUANTITY_PLUS}}</th>
					<th>{{VALUE_SPACER}}{{VALUE_SPACER}}</th>
					<th>{{VALUE_BUTTON_CART}}</th>
					</tr>
					</table>
					</div>{{VALUE_CHECKOUT}}
            </div>
		<div class="col-md-12">{{VALUE_SPACER}}{{VALUE_SPACER}}{{VALUE_SPACER}}{{VALUE_SPACER}}{{VALUE_SPACER}}{{VALUE_SPACER}}{{VALUE_BUTTON_CONT}}
		</div>
		<div id="outStock" style="display:none"><strong>{{VALUE_STOCK_OUT}}</strong>{{VALUE_STOCK_OUT_DETAILS}}
		</div>
		<br><br>
		<div>
		<h4>{{VALUE_PRODUCTS_URL}}</h4>
		</div>
		{{VALUE_EXTRA_MODULES}}
		{{VALUE_TABLE_HEAD_LAST}}

		{{VALUE_HIDDEN_VALUES}}
	</div>
</div>
</section><!-- #performance --><!-- end product -->
</form>
</div>
{{VALUE_START_SCRIPTS}}
{{SECTION_PRODUCT_END}} 