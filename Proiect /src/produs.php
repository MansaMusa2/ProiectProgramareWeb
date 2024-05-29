<?php
    class Aliment{
        public $name;
        public $img;
        public $price;
        public $tip;

        public function __construct($name, $img, $price, $tip)
        {
            $this->name = $name;
            $this->img = $img;
            $this->price = $price;
            $this->tip = $tip;
        }

        public function DisplayCart() {
            echo "<div class='col-md-6'>";
            echo "<div class='box'>";
            echo "<div class='img-box'>";
            echo "<img src='images/" . $this->img . "' alt=''>";
            echo "</div>";
            echo "<div class='detail-box'>";
            echo "<h5>" . $this->name . "</h5>";
            echo "<h5> $" . $this->price . "</h5>";
            echo "<g>";
            echo "<path d='M345.6,338.862c-29.184,0-53.248,23.552-53.248,53.248c0,29.184,23.552,53.248,53.248,53.248 c29.184,0,53.248-23.552,53.248-53.248C398.336,362.926,374.784,338.862,345.6,338.862z' />";
            echo "<path d='M439.296,84.91c-1.024,0-2.56-0.512-4.096-0.512H112.64l-5.12-34.304C104.448,27.566,84.992,10.67,61.952,10.67H20.48 C9.216,10.67,0,19.886,0,31.15c0,11.264,9.216,20.48,20.48,20.48h41.472c2.56,0,4.608,2.048,5.12,4.608l31.744,216.064 c4.096,27.136,27.648,47.616,55.296,47.616h212.992c26.624,0,49.664-18.944,55.296-45.056l33.28-166.4 C457.728,97.71,450.56,86.958,439.296,84.91z' />";
            echo "<path d='M215.04,389.55c-1.024-28.16-24.576-50.688-52.736-50.688c-29.696,1.536-52.224,26.112-51.2,55.296 c1.024,28.16,24.064,50.688,52.224,50.688h1.024C193.536,443.31,216.576,418.734,215.04,389.55z' />";
            echo "</g>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }

        public function Display(){
            echo "<div class='col-sm-6 col-lg-4 all ".$this->tip."'>";
            echo "<div class='box'>";
            echo "<div class='img-box'>";
            echo "<img src='images/" . $this->img . "' alt=''>";
            echo "</div>";
            echo "<div class='detail-box'>";
            echo "<h5>";
            echo $this->name;
            echo "</h5>";
            echo "<div class='options'>";
            echo "<h6>";
            echo "$".$this->price;
            echo "</h6>";
            echo "<a href='addCart.php?name=" . urlencode($this->name) . "&img=" . urlencode($this->img) . "&price=" . urlencode($this->price) . "&tip=" . urlencode($this->tip) . "'>";
            echo "<svg version='1.1' id='Capa_1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' viewBox='0 0 456.029 456.029' style='enable-background:new 0 0 456.029 456.029;' xml:space='preserve'>";
            echo "<g>";
            echo "<g>";
            echo "<path d='M345.6,338.862c-29.184,0-53.248,23.552-53.248,53.248c0,29.184,23.552,53.248,53.248,53.248 c29.184,0,53.248-23.552,53.248-53.248C398.336,362.926,374.784,338.862,345.6,338.862z' />";
            echo "</g>";
            echo "</g>";
            echo "<g>";
            echo "<g>";
            echo "<path d='M439.296,84.91c-1.024,0-2.56-0.512-4.096-0.512H112.64l-5.12-34.304C104.448,27.566,84.992,10.67,61.952,10.67H20.48 C9.216,10.67,0,19.886,0,31.15c0,11.264,9.216,20.48,20.48,20.48h41.472c2.56,0,4.608,2.048,5.12,4.608l31.744,216.064 c4.096,27.136,27.648,47.616,55.296,47.616h212.992c26.624,0,49.664-18.944,55.296-45.056l33.28-166.4 C457.728,97.71,450.56,86.958,439.296,84.91z' />";
            echo "</g>";
            echo "</g>";
            echo "<g>";
            echo "<g>";
            echo "<path d='M215.04,389.55c-1.024-28.16-24.576-50.688-52.736-50.688c-29.696,1.536-52.224,26.112-51.2,55.296 c1.024,28.16,24.064,50.688,52.224,50.688h1.024C193.536,443.31,216.576,418.734,215.04,389.55z' />";
            echo "</g>";
            echo "</g>";
            echo "<g>";
            echo "</g>";
            echo "<g>";
            echo "</g>";
            echo "<g>";
            echo "</g>";
            echo "<g>";
            echo "</g>";
            echo "<g>";
            echo "</g>";
            echo "<g>";
            echo "</g>";
            echo "<g>";
            echo "</g>";
            echo "<g>";
            echo "</g>";
            echo "<g>";
            echo "</g>";
            echo "<g>";
            echo "</g>";
            echo "<g>";
            echo "</g>";
            echo "<g>";
            echo "</g>";
            echo "<g>";
            echo "</g>";
            echo "<g>";
            echo "</g>";
            echo "</svg>";
            echo "</a>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";  
        }
    }

    class Cart{
        private $aliments = array();

        public function addAliment($aliment){
            $this->aliments[] = $aliment;
        }

        public function Total(){
            $sum = 0;
            foreach($this->aliments as $aliment){
                $sum += $aliment->price; 
            }
            return $sum;
        }

        public function rmAliment($name){
            foreach ($this->aliments as $key => $aliment) {
                if ($aliment->name == $name) {
                    unset($this->aliments[$key]);
                    $this->aliments = array_values($this->aliments);
                    break;
                }
            }
        }

        public function dispCart() {
            foreach ($this->aliments as $aliment) {
                $aliment->DisplayCart();
            }
        }
    }
 
    $aliment1 = new Aliment("Delicious Pizza","f1.png","20","pizza");
    $aliment2 = new Aliment("Delicious Burger","f2.png","15","burger");
    $aliment3 = new Aliment("Delicious Pizza","f3.png","17","pizza");
    $aliment4 = new Aliment("Delicious Pasta","f4.png","18","pasta");
    $aliment5 = new Aliment("French Fries","f5.png","10","fries");
    $aliment6 = new Aliment("Delicious Pizza","f6.png","15","pizza");
    $aliment7 = new Aliment("Tasty Burger","f7.png","12","burger");
    $aliment8 = new Aliment("Tasty Burger","f8.png","14","burger");
    $aliment9 = new Aliment("Delicious Pasta","f9.png","10","pasta");
    $Cart = new Cart();
?>