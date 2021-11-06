{{SECTION_NO_PRODUCT_START}}
<div>{{VALUE_NO_PRODUCT}}</div>
{{SECTION_NO_PRODUCT_END}}
{{VALUE_FORM_START}}
{{SECTION_PRODUCT_START}}
<section id="performance">
<div class="container-fluid">
	<div class="section-header">
		<h2>{{VALUE_CATEGORIES_TITLE}}</h2>
	</div>
	<div class="row">
		<div class="col-lg-9 content">
		<h4>{{VALUE_CATEGORIES_DATE}} at {{VALUE_CATEGORIES_TIME}}</h4>
		<h4>{{VALUE_CATEGORIES_VENUE}}</h4>
		{{VALUE_CATEGORY_DESC}}
		</div>
		<div class="col-lg-3 performance-img" style="text-align:center">
			<img class="img_fluid" src="images/big/{{VALUE_CATEGORY_IMAGE}}" alt="{{VALUE_CATEGORY_TITLE}}">
		</div>
	</div>
 </div>
</section><!-- #performance -->

<div>{{VALUE_GA_TOTAL}}</div>
	<div id="ga_listing" class="container  my-4 pb-2 mb-3">
      <div class="row p-3 text-dark bg-light gat">  
        <div class="col-md-7">
        <strong>{{VALUE_TEXT_TYPE}}</strong>
        </div>
        <div class="col-md-2">
		<strong>{{VALUE_TEXT_PRICE}}</strong>
        </div>
        <div class="col-md-3">
		<strong>{{VALUE_TEXT_QUANTITY}}</strong>
        </div>
      </div>
			{{REPEAT_PRODUCT_LIST_START}}
				  <div class="row p-3 effects">
					{{REPEAT_PRODUCT_LIST_IMAGE}}
					  <h4>{{REPEAT_PRODUCT_LIST_NAME}}  {{REPEAT_PRODUCT_LIST_QUANTITY}} {{REPEAT_PRODUCT_LIST_WEIGHT}}</h4>
					<strong>{{VALUE_CATEGORY_DATE}} {{VALUE_CATEGORY_TIME}}<br>
					{{VALUE_CATEGORY_VENUE}} </strong> 
					<br>{{REPEAT_PRODUCT_LIST_DESCRIPTION}}
					</div>
					<div class="col-md-2">
					  <h4>{{REPEAT_PRODUCT_LIST_PRICE}}</h4>
					  <small>{{VALUE_SALEMAKER}}</small>

					</div>
					<div class="col-md-3">
					<table id="gaq">
					<tr>
					<td>{{REPEAT_PRODUCT_LIST_BUY_NOW}}</td>
					</tr>
					</table>				
					</div>	
				</div>
				<div><strong>{{VALUE_TEXT_PLEASE}}</strong><br></div> {{VALUE_FORM_END}}<br>
				<br class="clearfloat">
				
				{{REPEAT_PRODUCT_LIST_END}}
			</div>
			
			{{SECTION_PRODUCT_END}}
  </form>
