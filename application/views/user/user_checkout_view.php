<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<div class="inner-pages">
		<div class="checkout-page">
			<div class="container">
				<div class="choose-a-plan">
					<h1>Choose a plan</h1>
					<div class="checkout-page-inner">
						<div class="planchoose regular-plan">
							<div class="plan-head">
								<h2>Basic</h2>
								<span>$29/month</span>
							</div>
							<div class="plan-inner">
								<ul class="plan-text">
								    <li>
										<i class="fa fa-check"></i><span>Only for 2 Stores</span>
									</li>
									<li>
										<i class="fa fa-check"></i><span>View products</span>
									</li>
									<li>
										<i class="fa fa-check"></i><span>Import products</span>
									</li>
									<li>
										<i class="fa fa-times"></i><span>Edit products</span>
									</li>
									<li>
										<i class="fa fa-times"></i><span>Facebook video training</span>
									</li>
								</ul>
							</div>
							<?php if(isset($user_plan) && $user_plan == 'Basic plan'){ ?>
									<div class="paid">Activated</div>
							<?php }else if(isset($user_plan) && $user_plan == 'Premium plan'){}else{ ?>
								<form action="" class="import-checkout" method="POST">
									<input type="hidden" name="subscription" value="1">
									<div class="pay-now">
										<input name ="plan" type="submit" class="checkout-button" value="Buy">
									</div>
								</form>
							<?php } ?>
						</div>
						<div class="planchoose premium-plan">
							<div class="plan-head">
								<h2>Premium</h2>
								<span>$99/month</span>
							</div>
							<div class="plan-inner">
								<ul class="plan-text">
									<li>
										<i class="fa fa-check"></i><span>Unlimited Stores</span>
									</li>
									<li>
										<i class="fa fa-check"></i><span>View products</span>
									</li>
									<li>
										<i class="fa fa-check"></i><span>Import products</span>
									</li>
									<li>
										<i class="fa fa-check"></i><span>Edit products</span>
									</li>
									<li>
										<i class="fa fa-check"></i><span>Facebook video training</span>
									</li>
								</ul>
							</div>
							<?php if(isset($user_plan) && $user_plan == 'Premium plan'){ ?>
							    <div class="paid">Activated</div>
							<?php }else{ ?>
								<form action="" class="import-checkout" method="POST">
									<input type="hidden" name="subscription" value="2">
									<div class="pay-now">
										<input name ="plan" type="submit" class="checkout-button" value="Buy">
									</div>
								</form>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>