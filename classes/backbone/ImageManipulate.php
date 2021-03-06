<?php
	namespace backbone;
	class ImageManipulate extends \backbone\ImageBase{
		public function Resize($newWidth, $newHeight, $stretch = true){
			// ====> if $stretch is false <====
			//		This function will NOT return an image with the exact dimensions specified.
			//		Instead, it will return an image with the LARGEST dimension matching the size indicated,
			//			and the other dimension scaled appropriately.

			if( $stretch ){
				$new = new \backbone\Image($newWidth, $newHeight, $this->caller->type);

				if( imagecopyresampled($new->handle, $this->handle, 0, 0, 0, 0, $newWidth, $newHeight, $this->caller->width, $this->caller->height) ){
					$new->SafeSource( $this->caller->source );
					$new->destination = $this->caller->destination;
					return $new;
				}
				else{
					return false;
				}
			}
			else{
				if( $this->caller->width > $this->caller->height ){
					$perc = ($newWidth / $this->caller->width) * 100;
				}
				else{
					$perc = ($newHeight / $this->caller->height) * 100;
				}
				return $this->Scale($perc);
			}
		}

		public function Scale($percent){
			if( $percent < 1 ){
				return false;
			}

			$scale = $percent / 100;

			$w = $this->caller->width * $scale;
			$h = $this->caller->height * $scale;

			$new = new \backbone\Image( $w, $h, $this->caller->type );

			if( imagecopyresampled($new->handle, $this->handle, 0, 0, 0, 0, $w, $h, $this->caller->width, $this->caller->height) ){
				$new->SafeSource( $this->caller->source );
				$new->destination = $this->caller->destination;
				return $new;
			}
			else{
				return false;
			}
		}

		public function Crop($top, $right, $bottom, $left){
			$x = $left;
			$y = $top;
			$width = ($this->caller->width - $left) - $right;
			$height = ($this->caller->height - $top) - $bottom;
			$new = new \backbone\Image( $width, $height );

			if( imagecopyresampled($new->handle, $this->handle, 0, 0, $x, $y, $width, $height, $width, $height) ){
				return $new;
			}
			else{
				return false;
			}
		}

		public function Rotate($angle){
			//always make the uncovered color transparent
			$rgba = \backbone\Color::HexToRGBA("#FFFFFF", 0);
			$color = $this->AllocateColor($rgba[0]['r'], $rgba[0]['g'], $rgba[0]['b'], $rgba[0]['alpha']);

			$img = new \backbone\Image();
			$img->handle = imagerotate($this->handle, $angle, $color);
			return $img;
		}
	}
?>
