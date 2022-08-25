@if(Session::has('user') && !$retirado)
<div class="favoritos">
	<a @class(['lb-text-primary', 'hidden' => $lote_actual->favorito]) href="javascript:action_fav_modal('add')">
		<svg class="bi" width="24" height="24" fill="currentColor">
			<use xlink:href="/bootstrap-icons.svg#heart-fill"></use>
		</svg>
	</a>
	<a @class(['lb-text-primary', 'hidden' => !$lote_actual->favorito])  href="javascript:action_fav_modal('remove')">
		<svg class="bi" width="24" height="24" fill="currentColor">
			<use xlink:href="/bootstrap-icons.svg#heart"></use>
		</svg>
	</a>

</div>
@endif

{{-- mobile --}}
<div class="slider-thumnail-container d-sm-none">

	<div class="owl-theme owl-carousel" id="owl-carousel-responsive">

		@foreach($resourcesList as $resource)
			<div class="resource-wrapper">
				@if($resource["format"] == "GIF")
					<img class="img-fluid" src="{{$resource["src"]}}" alt="{{$lote_actual->titulo_hces1}}">
				@elseif($resource["format"] == "VIDEO")
					<video width="100%" controls>
						<source src="{{$resource["src"]}}" type="video/mp4">
					</video>
				@endif
			</div>
		@endforeach

		@foreach($lote_actual->imagenes as $key => $imagen)
			<div class="image-wrapper">
				<img class="img-fluid" alt="{{$lote_actual->titulo_hces1}}"
					src="{{Tools::url_img('lote_medium_large',$lote_actual->num_hces1,$lote_actual->lin_hces1, $key)}}">
			</div>
		 @endforeach
	</div>

	@if(!empty($lote_actual->contextra_hces1))
		<div id="js-360-img-mobile" class="img-360-real img-360-mobile-content" style="display:none"></div>
	@endif

</div>

@if(!empty($lote_actual->contextra_hces1))
	<div class="d-sm-none">
		<div id="js-360-btn-mobile" data-active="disabled" class="img-360 img360-mobile col-12"
			style=" background-repeat: no-repeat;background-position:center;background-size: contain;background-image: url('/themes/demo/img/{{$lote_actual->imagenes[0]}}')">
			<img style="position:relative; z-index: 1;" width="40px"
				src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyFpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTQyIDc5LjE2MDkyNCwgMjAxNy8wNy8xMy0wMTowNjozOSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpFRDM4OEVEMDNEOUExMUU4QTQ1QkRDOEFFM0VERUZFMyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpFRDM4OEVEMTNEOUExMUU4QTQ1QkRDOEFFM0VERUZFMyI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOkVEMzg4RUNFM0Q5QTExRThBNDVCREM4QUUzRURFRkUzIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOkVEMzg4RUNGM0Q5QTExRThBNDVCREM4QUUzRURFRkUzIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+43smUgAAFppJREFUeNrsXAl0G/WZH2l0n9ZhSZYs2Y6dWD7jOM7lnPRRdiEsN20DKeW9wj42LGXhdZc+tqUsV1nOlAKlaRfo4yi0HE157BIWsuRw4iR24tiOHTs+5Fu2dVq3NCPt95c1zngsOTaxIYb53ptoPJn5z/y/33f/vxlOIpHAWLp0iMuygAWEJRYQFhCWWEBYQFhiAWEBYYkFhAWEJRYQllhAWEBYYgFhAWGJBYQFhCUWEBYQllhAWGIBYQFhad7Eo/8RjUYxu92+KDdC7UZkPI5xORwMx3EM53JxDPbJScLiiTgcQ4c4X2rsOIyNfunXo30ud3aZQ/dOMoLHQ+dy4BcHPhBoLAL+jzuHMS6G5HI5plKp0gPS2tqKrVmzZlHA4PP5vNwc45XZ2brtUomkAiapQQwj46QzFAq1jzscnwwNDX8cjoZDcwWF6ikTCUQKjUZdKZXJyuVSqZnD4SoQbycmJg53dJ17a7ZrszXZVpPReFOWUrmRi+NmeEw+ABIiCMLmcrkODI2MvO/xevopgBea7r33Xuy5555LD0hSUkHSFpqyFFlbyqzWp2Uy2VoyJcmUVKNJSiXSWkWe7McGfU5r29kzD7jc7v+ey7hiobC0cFnRLnWW6hqhSGhOMg2NC798nI/FIhEF3OOtDJcLyqwlj+UYcv4ZnkGcnDdcG41Ek88k5AlWwv9dq9fpf95rsz3Z09f79GI0FYbD4cwmazEkwKA33LKqYuVr8TgpIFNgU/eh34+EyUrFovL1Nes+amtvu8M22P/abOOWrLD+Ii/X/ACHy5USJDEl8RTLECwJDjeU7lq4K29tzZo/aVSaG6Kx6DRTR/0mJtUImSt1SbH1KYVCmdvU0nTvgjtxhjlcVKeuylLVAhivA8OmwJjVnsM5MSLGLbGWvCKXyqrTMpPD4a9dXfN2viXvEZBqKZyPzVdyrSusP6PAmItZjEQjmNFg+InZZLp1KUdZvFJr6W7QDD6TYTxw6mCqkxvaZzIgkYgLrMXF/5Fu0Mqy8he0au0OBEQcSzDBQsFCckwc58HGFTGvFwoEuZbc3AfQ9cxrqWdC5o6LcRjOn8AKCwofhfOkX1mUtZCkVqlqFTLZGubEgWEJ+9jo6yOjo58jJuiz9VsMuuwfg3ZMIRMHXwZ+4btCgTAfpNNGHTflGG8w5ZjuYko2GocHAMDxiWAkfA7s8jhEbHFwxseZz2XJNd8KAZ6MpD0Xuh60baSjp2O3z+8fyNZqS405xn+B4zJKmJD2SsSSAqMh5++HRobfX3KAaNWabTNuBpLbN9D/SHtnx8PUMfuo/S1ORSVh0Ol3EakQNOmUubhQpVBW2R1jthTTxEXLCn+FJJUBMGKWo7Or8/G+gYG/gHkcmvW5tNrtoLXMMYim5qbvjzudh9Df404HNjHhO1dRVv5HukCh6wCsaxYTkEUzWRDP56VzEz19treZB4eGhz6BUHXGyRKpxETtgw2/BiR0Bd0XIcmOxmIddceOVnfbendfCAwUasMYZXGaCUWAen2+4xQYU89kH34nFA710wMPpC1yqXzVovJt0ZCG6AdyD+YxmD+XmOE4UVaY3oFzzwNi3JlgnAZJW2xweOglYFKVRqW+FlgXDYXDNrfXcwpC+PEZYbJYnMvHeWqCpiGI4V6vtz6NM48GAoEmjVptmdJcAEQgFJhA09VwzLGkABkYHHjZFwh8Bv4gQc2QiMf9kAT2Mc81my3fS6TBxB8M9ExKMa5WKBQbmJEa/M3NM1ue4hfwRalwNunmwZeMQaL57pmzbY8BMGPU+eCs9ZMY0wHhYv5AwJZuDnC8X6vRTp2PNATAUAgEAg0RCi0tQMAMHEQb/Rhk6MVKuXI1Z9IOcCCZM5hNuT/QqFTfp6SQklqw3WBFnEeS5QWZvIzP42no51AWB23MwAG0UAcBwD1arebKxpOnbvT6vM3J4zhXkVYTuRxv+rJK3JuBZ7IlZ7KYBOGkZcuGzU3AbNFUGMxBUhfHmIxGoWdvX+/zcNw9We+RWZmSfaHcAYEk4PGLaqqr/3ag7tB6giDscrlcgKXPfTMNTGTK8pd8tTfJJDKWgA2b2oBpTDCQk3W4nB+e7ex4hjomk8rysFlyP6RR1DaNmzC2gC/IW1la8fT5JH1BiLPkAaEixwvOFJjq8020AP9jtIhNm+lclMSB9PdHo9EmAD2IM0oRCPTsbO0OsPsWkiDCS6r8vsiEXAcflbPjjIorU6rzLfkPhSKREVuf7ZXUedJ0YCC/297VcX//QP87iXjcJ5FIytZU17wL2XjZ9PCYi5uNpusjkUj3pQ7IV6khJDDWDqGjM5HagKlh5C9mmhoCW76s8EmIaIw05z0j4+/s7toFoP0esmwfgjYQDJ45035mF4w3TRNRQqdQZG3kgZoslAVe8hoCTB7cf/CLMuTUaZm7Qpet2wgZ+GNglixTFVu0fsLjK/Mted/r6u3ZnaEQGYKE8jPmcQjN6sKgCUK+YDlJC6UFAr6ZiMUEifQpTybBxDMcjy05QMRCcREIpIjOAAAlEAyFes+HlaRjYGiwxx/w962trtkPKoSfDwLimFKZtRl2dyNNSvfsGQp9JCR0QyK1cDkVO6GETiQSysCsRdOZSUhM04bDOM6VZ4jIAksOkFUVlX9WqdRVZJyYSsAgYesALakEpkyTMO/ExHHwHXbIH0x0LRGJxHq0Dw57ZGZdjCeQiqV6T8zTlaZKgDONCuQUGEmQIylzM81GioQic7o5yKRSE12gkGmF5/TD87iWnA8h4iROkgQHJpDaCA5ohCiD/eWkCyXB9ie5MeH3taULNHXZ2svTOHsROPcCurlCgQSYK5/D4zoHz+Kl+yzkX7KylDXpyjYwTgWzOgBRmx3GcC45QGBCTmYeAtGPUSmXVzLPVSoUVZCJ6+nmBDEtHA4nNcPj9tQD42JMx5+ba/5HGNMwrQxjMl8PEp9LH4vLwTEwlZ0QHjvCkXAXlw4InKdSKmtRFYE+jipLtVksEq1IMAqREz5/0ywJ46UFCJgKBTdl131+XwOHkRPA5AWrVq76k1FvuE4oEJrRlq3R/kNlWcWrYMfx6YDC5Ce8dWg/FAl1+3y+g/QcI+n4IQpbv2bd3ixF1mYYa1mu0XTLiqKi3xCMEj2Hy8FcLtenaN/hcH6KLBo2PfQWr15Z/SaMswHGMapV6ssqy8pfAe2YoZeRcLhuRWHRv9esWv12fl7+PVKJtGRBBZkuAQ0NDfPuOkFVXYiULs81Gm9SyJXfQRXxjs6Onzo97rattZvOIHPFzB9SaxiBlNRJAQxsmolJMpEb+OLwwZJINDqAjuXo9H9XVVn1SbqVvtR4YR6XKyJSTRTT7D5BDB6oO1QG4fEEYuCW2k3NMA6PWSGY7IKJ+2BfTjLGQS1LcZIcJGLECalUej3yLUhoEgky5vcHT4yOj77fNziwF/zLvHKdu+++G3vxxRcvXkMgiious5Y+vnXTltOrKlb+VavW7ISoxAihbL612Pp7lIQN20f2CPiCGSUUlPyhZA9taJ9khKI8uGZweOgZCgxEI2Oj+8adjrdR3pJhPFFs8ndGXaynr/cXCAwsmasE2oftw68yx0EApMaRE+nGwXloMe2VGEmYkd9B50yWfuJ8qVRSW7Ss6NmtG7echmjxfb1OfxUn3QLPYpgsUOvqmlXVr2+qrT1pyTU/CAAUUjUpqhkO5RfwQKrWtjP3e33eg0xQZiN0rsvter+t4+zjzP871dy0KxgO1jOZmYkgF8FAcn/b19//Ov04PNfPQuFQM3M9f5bCKAYmc397Z8d/+nwTB/kwLt18ppozUKguVatUN1RXVn0MWlifZ8nbCXzAFwUQkUBkqaqo3LNh7bp60IYfAfMl9I4Pqq4k5Asx0IwX0DoE+Ixg/fFjV4+Ojr5KNQ+kazWiGgyAQQRoxrPHGk/sYIbGqbzFW1d/dLvL4/oAAYcYyhxv8jmSTRREb7/tiZOnm3alSSrdR47Xbwcmfy5IMTfdOGj8lIB8UN9w7Ea0pHPmbPvPO7s6/83v99fBdclKAx0cSnNEQuGasuKSNzav33gENObKBfUhBZb8O4oKC5+A2D87TdMChpwkRC82h9P5EVpvhgkcmKlZis3gBG+H6GULZOUmuE5EZdyxWKwfrvm/XpvtVV/A3zCXBzcZcq4zmXLvkMtkayFCU6NHQat80VhsyOP17rf12/a4PTObHJgCmZtjvBU0/YdiiaSKGgcCcyJKxBzA9BP9Q4Ovgan6MN3FErF4RY4h5ypDtu4muVxey4F4jogTGDMyw7k8bNQx+sfm1pZ/jRHE+Gw+ZFZAwGHLVlWufFmfrf8hs/8pKZ2gYG6ve9/g0OCrw6P2/0E1pTmEw0I+n58D/yiRC4jEYh4AZCSdRswxqNCIRWIDBFKo98sXCoeHUdV3vuPgXFwjEol0MCkxTDQI44zCfNxzvR7C+RqLOe82vU63A7RGS2/eo8xeJBo5d6r59O1uj/tIJkAyZuo4jmtr16z/QCaTbaa33VAaAYO+19XT/ZzD5Tw6z3UR8NVRG2rsXpB6fjzuBEd90Yka6jG+mHG8Pl9DS1trQ0eX4FfLlxXeacwx7gIQ9JRFQb+ggcvXra7Zd7ql+RYIUj6asw8BKZYAGB9C9LCZbqIQyoFQ6EjDyYbL6huO3zxfML4NhMo84GceOXD4UDVEc78BSxKnfAxy/rDJqiqq/gz517YLAkKpWEVp2bOgGZuo1byUg4v32GwP1dUf2Trucn7Bsv4CwMSiw6dbW37ScLLxu+DXzlERXbLJPEGKKssr34Q/9SRjxZTLiGKQLdxoNOTcRWlGKvEKNLW23NDR1fEoijRYds+dHG7X/kNH6rYEAsHDFChIUwR8vslisjyMMaI7LsNvYIX5hfehzJnmM0iIDnZCpLGXZe+XLLSShP3oifprguFwC2W+kMDrddm3qbKyzBkBkUmkKrlCvpVqJEOIDtvt/wUR1F9Ztl4sKKS7uaXlLrA4BC37l+g02u9kBIQn4FsgjKQ3FCT6Bwb+wLJzYcgz4Tni9wdOUlqCuveFQmHFbE5dyPg7HgovTofet5UisYiD0S8smS3sRSthsfNJF46rVKoalo0LQ6gyLpfJKxmLXiMZAQkHQ33RSKSbQhBVNVcsK3oQZdcsOy+eigqW3SfkC6cWzziJ5PL1kYyABCOh2Khj/B1UBKTCM4lEXFVTVb2Hg3E4LEu/POUajTcuyy94KEaeTycC4WDnmGO8LiMgwHSsx9b7Eti5EUpLUHKo1WhvW1O9+l2IulRfxcOjbhK9Tn8NhIQbvwlg5Ofl31leUvY2CDifXvXo7u19NBQKhWfNQ+LxuONsx9l/oq85oJhZrVLdvLl20yEI0y5fZDur2Lh2w6eglXs3rNlwuLS45JdLFQhURF1dteq1kuXFe0CwBZSpQrx1uJzvBoKBN3HGmkzaWhbkHXs7znX+VMATYHRNgeyyDG7wv2iBSiwUlyzGJDQq9TalUlmL3nyNxiJYntny8LK8/NuXEhBIw9F6+7baTQ3ZGu3tzHrgxIT3QMOpxjso0zUt9cg0aLet51kUpa0oWr47TpI4WmZNFsew5PuDP9pUW3vz6NjYW339tt95fb7GBUugorFxetkalbHhGV7x+f29407HgUsZCDDpOrPZ8gOLKXeXRCwpRs9O7+5Hi10ej/vj+sYTt4Il8qe1ELPdAEB5sbGpcTtk7r10E5ZarpWYcox3bli7/tj6mrX7co2mnZm61OdDbp/36ODw4ItIO1NxOhIE4cqKyndQN+QlqA08MOebVpaWv4T6C0qKin8tEAiK6etHKBEE/iX6BvqePNpw/DoAw5sR1AvdcMzh2Hew7vD6cmvpo3q9DqkZl0I9pYq4UqG4QpWlusK6onjU7XLvH3WM7bWPjR4iCGL4y0yype3MfRKJpFClVF1JTQwmZVhbU/OXQ0frts02oa8IBAkEHNU5esNVGpXmaqlUWoGhF4/AgkSJKP28pIny+/2N7Z1nHwAN//yCY8+nDUitUtdai5Y/qFRmbU/35hPVkoMaLoCRHn/A3+h2e79wuhx1Hq+nZT4vSqIXKzdt2HhAJBCUE6lECk3O5XH/7VjD8euxObxrsoAACGUS6Qq1RrNOm6XeJlfIa0FbC1A3ZTxN1wy1iAd+sLt/oP/5blvvH9DCXLqx57WEm4myNZqtBXkFd4OUXA03FjOXKylwJj9tNBlFRGNRRyAYbPP7fSc9Xu9Jr9fbFgqH+mYDCexw0Ya16w5D5KWnRyjD9uHnT7e23L9YDlnI55vkckVxllK5UqlQrpbJZOUiobAAvWeSSPnSdPOlhDEQCjYNDw/t6emzvUW1H2WiOS/hzkbjTucBtIGUWC1my44cg/4msUhcSjlh6ms/ZKotKBXOapVy+RaVMmuL2WROdrfHCMIZjUQHUGdiMBjs8geCPeFwuM8f8I0SBDkWDAX7O8917rQWW9GL+grKTJoMpvsCgUBXV2/Py8xIExiD1urJlAYlUr/0jYt6BcBHqUVikV4kEpnlUmk+mMgiiUhcKBSJ8gQ8fg4XxwWTPiw+1bPFfBWRYRGcYJL2DQ4PvzE2PvbZl103uqjud2Dk2Y6ujl92dnc+jj40YzQYroMk8gqQppLJrr7z0sQEKAWSRiIRa6RSSRVHkz11HJVsyHjCT5KEJxaNjZAkmaB/NYcAUJYXLv81aNpZiOf3Y8nIT31ZeWn5C8AkY4oZJA0EkvaL3pXP4vN4cvRGF9XPhp51cjVvsouSJOJpIyBOyhylhGPM6XbV2e128Jlj+1Aj9kVHaguh5qj9xuV2fYE2NEm5VLZSl63bBtHHNlD3ashfciiAqEnTQZqkGUuZMg6PJ4PILZdpHlDZGljCy8u13I8AgXsKKsoqfiuE6Iac4/e+zp9Hzma+pjQgpf1+0OJ2r9d9GO77ucPpPEYu8AcEFvz9ENTOM+H3NaAN68WeQdKokClK1aqs1QqFokYulVcIRcICcNpZ6aSTYn588j8uZPHjKcbx+ThPk862z8FnTMb+NH+X0lKwptEhbzB41jsx0eiZ8B53u91N4AsHFjWXWewIBZyaBy3MoG3qpjyeQSqWFsrlsmKFXG4Vi8VFQqE4TyQU6HEc16AmOgosZmMeLbsN9Q7ankzdIzA4Mvx8gSXvcYRHYg6vAKaEgETvi0QgGYWIaAD8WHcgGDjr8/s6IFTtCkejg3BO6CtNLr+OOB69xO/1edFWx6xjoS82gInTS6SybNAiTUF+wQNikcjK6AaMN7c23+ZyuaZAbutof2J4ZOgzmUyO3lVE88JTG32fn3L042B7RgHs0XAkjF5AdV0qzRs87BIiFCKCmUBbry8QwCy5lh0AhoUOBio/nOs+d8+QfeQ95vWeiYnjaFvKleFL9ru9Br3+hjJryRv0Jc5UyfqRcz3dL2PfULokAdFpdZuryivfgKgGp4MxNjb2OxRmY99guuQAgey8tKq84j2ImCSM9YMPGptP3Y19w+mSA6S02PoYzuPpKDBQb5gv4D/U2HTyNmyunwNiAVk4wnGemspLUFIWCofPHD1ef2M89U7itwqQxfiq9XzpTFvrU4FA0BeLxjCP19t+6Gjdtek+1/dNIeZrGdOqvV6vF6uvr/9aHxA9j1KpLBQJhAWBgL8Bwl/PYn4M/+smi8WClZSUpAeEJdaHsMQCwgLCEgsICwhLLCAsICyxgLCAsMQCwhILCAsISywgLCAsLQT9vwADAFBa90h21CNjAAAAAElFTkSuQmCC">
		</div>
	</div>
@endif



{{-- desktop --}}
<div class="d-none d-sm-block">
	@if($retirado)
		<div class="retired">
			{{ trans(\Config::get('app.theme').'-app.lot.retired') }}
		</div>
	@elseif($fact_devuelta)
		<div class="retired">
			{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
		</div>
	@elseif($cerrado && (!empty($lote_actual->himp_csub) || ($sub_historica && !empty($lote_actual->impadj_asigl0))))
		<div class="retired">
			{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}
		</div>
	@endif
	<div id="resource_main_wrapper" class="text-center" style="display:none"></div>

	<div class="img-global-content position-relative">

		<div id="js-toolbar" class="toolbar d-flex alig-items-center gap-2" style="position: absolute; z-index: 999">
			<a id="zoom-in" href="#zoom-in" title="Zoom in">
				<svg class="bi" width="24" height="24" fill="currentColor">
					<use xlink:href="/bootstrap-icons.svg#plus-circle"></use>
				</svg>
			</a>

			<a id="zoom-out" href="#zoom-out" title="Zoom out">
				<svg class="bi" width="24" height="24" fill="currentColor">
					<use xlink:href="/bootstrap-icons.svg#dash-circle"></use>
				</svg>
			</a>

			<a id="home" href="#home" title="Go home">
				<svg class="bi" width="24" height="24" fill="currentColor">
					<use xlink:href="/bootstrap-icons.svg#house"></use>
				</svg>
			</a>

			<a id="full-page" href="#full-page" title="Toggle full page">
				<svg class="bi" width="24" height="24" fill="currentColor">
					<use xlink:href="/bootstrap-icons.svg#arrows-fullscreen"></use>
				</svg>
			</a>

			<a id="previous" href="#previous-page" title="Previous page">
				<svg class="bi" width="24" height="24" fill="currentColor">
					<use xlink:href="/bootstrap-icons.svg#arrow-left-circle"></use>
				</svg>
			</a>

			<a id="next" href="#next-page" title="Next page">
				<svg class="bi" width="24" height="24" fill="currentColor">
					<use xlink:href="/bootstrap-icons.svg#arrow-right-circle"></use>
				</svg>
			</a>
		</div>

		<div id="img_main" class="img_single"></div>

		<div id="360img" class="d-none img-content">
			<div class="img-360-real">
				{!! $lote_actual->contextra_hces1 !!}
			</div>
		</div>

	</div>


	<div class="minis-content d-flex gap-1">

		@foreach($lote_actual->imagenes as $key => $imagen)
			<div class="mini-img-ficha no-360">
				<button onclick="goToImage({{ $key }})">
					<img src="{{ \Tools::url_img("lote_small", $lote_actual->num_hces1, $lote_actual->lin_hces1, $key) }}">
				</button>
			</div>
		@endforeach

		@foreach($resourcesList as $key => $resource)
			<div class="mini-img-ficha no-360">
				<button onclick="viewResourceFicha('{{$resource['src']}}', '{{$resource['format']}}')">
					@if($resource["format"]=="GIF")
						<img src="{{$resource["src"]}}" alt="{{$lote_actual->titulo_hces1}}">
					@elseif($resource["format"]=="VIDEO")
						<img src="{{ asset('/img/icons/video_thumb.png') }}" alt="{{$lote_actual->titulo_hces1}}">
					@endif
				</button>
			</div>
		@endforeach

		@if(!empty($lote_actual->contextra_hces1))
		<div class="mini-img-ficha position-relative">
			<button id="360img-button" class="mini-img-ficha-content img-360-desktop more-img img-360" data-active="disabled">
				<img class="img-360-background" src="{{ \Tools::url_img("lote_small", $lote_actual->num_hces1, $lote_actual->lin_hces1, 0) }}">
				<img class="img-360-cover"
					src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyFpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTQyIDc5LjE2MDkyNCwgMjAxNy8wNy8xMy0wMTowNjozOSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpFRDM4OEVEMDNEOUExMUU4QTQ1QkRDOEFFM0VERUZFMyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpFRDM4OEVEMTNEOUExMUU4QTQ1QkRDOEFFM0VERUZFMyI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOkVEMzg4RUNFM0Q5QTExRThBNDVCREM4QUUzRURFRkUzIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOkVEMzg4RUNGM0Q5QTExRThBNDVCREM4QUUzRURFRkUzIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+43smUgAAFppJREFUeNrsXAl0G/WZ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" />
			</button>
		</div>
		@endif
	</div>
</div>



@push('scripts')
<script>
$(function() {

	const images = [
		@foreach($lote_actual->imagenes as $key => $imagen)
		{
			type: 'image',
			url: '/img/load/real/{{$imagen}}'
		},
		@endforeach
	];

	const viewer = loadSeaDragon(images);
});
</script>
@endpush
