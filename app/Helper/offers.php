<?php

use App\Events\OfferProgressEvent;
use App\Models\Offer;
use App\Models\OfferUserProgress;
use Carbon\Carbon;

if (!function_exists('user_has_offer')) {
    function user_has_offer($user_id, $product_id,$quantity)
    {       

        $offer = Offer::where('product_id', $product_id)
                      ->whereDate('start_date', '<=',  Carbon::now()->addHour(3)->format('Y-m-d H:m'))
                      ->whereDate('expired_at', '>',  Carbon::now()->addHour(3)->format('Y-m-d H:m'))->first();

        if($offer){
            $alreadyUse = \App\Models\Order::query()->where('user_id',auth()->id())
                ->whereRelation('products',function ($query) use ($offer){
                    $query->where('offer_id',$offer->id);
                })
                ->first();
        }else{
            return null;
        }


        if ($alreadyUse){
            return 'already';
        } elseif($offer) {
            $progress = OfferUserProgress::where('user_id', $user_id)
                ->where('offer_id', $offer->id)->first();
            
            if ($progress){
                if ($progress->progress + $quantity >= $offer->target) {
                    return $offer;
                }
            }elseif ($quantity === $offer->target){
                return $offer;
            }
            elseif ($quantity > $offer->target){
                return $offer;
            }
          
        
        }
        return null;
    }

    function user_update_offer($user_id, $product_id,$quantity)
    {       
        $offer = Offer::where('product_id', $product_id)
                      ->whereDate('start_date', '<=', date('Y-m-d'))
                      ->whereDate('expired_at', '>', date('Y-m-d'))->first();
        if($offer){
            $alreadyUse = \App\Models\Order::query()->where('user_id',auth()->id())
                ->whereRelation('products',function ($query) use ($offer){
                    $query->where('offer_id',$offer->id);
                })
                ->first();
        }else{
            return null;
        }


        if ($alreadyUse){
            return 'already';
        } elseif($offer) {
           if ($quantity === $offer->target){
                return $offer;
            }
            elseif ($quantity > $offer->target){
                return $offer;
            }
          
        
        }
        return null;
    }
}
