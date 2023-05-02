import { __ } from '@wordpress/i18n';

import { getType } from '../cardType';
import { Title } from '../title';
import { Image } from '../image';
import './style.scss';

export const Preview = ( { props } ) => (
	<div className="EmbeddedTweet">
		<div className="EmbeddedTweet-author u-cf">
			<img
				className="EmbeddedTweet-author-avatar"
				src={ tcData.avatar }
			/>
			<div className="EmbeddedTweet-author-name u-pullLeft">
				{ __( 'Your Twitter name', 'jm-tc-gut' ) }
			</div>
			<div className="EmbeddedTweet-author-handle u-pullLeft">
				@{ tcData.twitterSite }
			</div>
		</div>
		<div className="EmbeddedTweet-text">
			{ 'app' !== getType( props ) && (
				<p>
					{ __(
						'The card for your website will look a little something like this!',
						'jm-tc-gut'
					) }
				</p>
			) }
			{ 'app' === getType( props ) && (
				<p>
					{ __(
						'Preview is not provided for application card',
						'jm-tc-gut'
					) }
				</p>
			) }
		</div>

		<div className="CardPreview u-marginVm" id="CardPreview">
			<div className="CardPreview-preview js-cardPreview">
				<div className="TwitterCardsGrid TwitterCard TwitterCard--animation">
					{ 'app' !== getType( props ) && (
						<div className="TwitterCardsGrid-col--12 TwitterCardsGrid-col--spacerBottom CardContent">
							<div
								className={
									'js-openLink u-block TwitterCardsGrid-col--12 TwitterCard-container ' +
									getType( props ) +
									'--small ' +
									getType( props ) +
									'--noImage'
								}
							>
								<div
									className={
										getType( props ) +
										'-image TwitterCardsGrid-float--prev'
									}
								>
									<div className="tcu-imageContainer tcu-imageAspect--1to1">
										<Image props={ props } />
									</div>
								</div>
								<div
									className={
										getType( props ) +
										'-contentContainer TwitterCardsGrid-float--prev'
									}
								>
									<div
										className={
											getType( props ) +
											'-content TwitterCardsGrid-ltr'
										}
									>
										<Title props={ props } />
										<p className="TwitterCard-desc tcu-resetMargin u-block TwitterCardsGrid-col--spacerTop tcu-textEllipse--multiline">
											{ props.meta.cardDesc }
											<span className="SummaryCard-destination">
												{ tcData.domain }
											</span>
										</p>
									</div>
								</div>
							</div>
						</div>
					) }
				</div>
			</div>
		</div>
	</div>
);
