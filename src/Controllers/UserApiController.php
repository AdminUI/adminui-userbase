<?php

namespace AdminUI\AdminUIUserBase\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\GenericNotification;
use AdminUI\AdminUIAccounts\Models\Account;
use AdminUI\AdminUIAccounts\Models\Address;
use AdminUI\AdminUIEcommerce\Models\Product;
use AdminUI\AdminUIEcommerce\Models\Wishlist;
use AdminUI\AdminUIAdmin\Traits\ApiResponseTrait;
use AdminUI\AdminUIEcommerce\Models\ProductReview;
use AdminUI\AdminUIUserBase\Resources\OrderResource;
use AdminUI\AdminUIUserBase\Resources\ProductReviewResource;
use AdminUI\AdminUIUserBase\Resources\ProductResourceWishList;

class UserApiController extends Controller
{
    use ApiResponseTrait;

    // 💎💎💎💎💎💎💎💎💎💎💎 USER WISH LIST 💎💎💎💎💎💎💎💎💎💎💎💎💎💎💎💎💎💎💎💎
    /**
     * This will add of remove product from the wish list
     * @param Colleation $product
     *
     * @return json $data
     */
    public function toggleWishList(Product $product)
    {
        $list = Wishlist::where('user_id', Auth::user()->id)
            ->where('product_id', $product->id)->first();

        if (empty($list)) {
            $list             = new Wishlist();
            $list->user_id    = Auth::user()->id;
            $list->product_id = $product->id;
            $list->save();
        } else {
            $list->delete();
        }

        return $this->respondSuccess('True');
    }

    /**
     * Get all user wish list
     *
     * @return [type]
     */
    public function getWishList()
    {
        $pagination = Request('pagination') ?? 10;
        $orderBy    = Request('orderBy') ?? 'cost_price';
        $order      = Request('order') ?? 'desc';

        $productIds = Auth()->user()->wishList()->pluck('product_id')->toArray();
        $products   = Product::whereIn('id', $productIds)
            ->orderBy($orderBy, $order)
            ->paginate($pagination);

        return $this->respondWithResourceCollection(
            ProductResourceWishList::collection($products)
        );
    }

    // 📮📮📮📮📮📮📮📮📮📮📮📮📮📮📮 USER ADDRESES📮📮📮📮📮📮📮📮📮📮📮📮📮📮📮📮📮📮📮📮📮📮📮📮📮
    /**
     * Return all the user accounts
     *
     * @return [type]
     */
    public function getAccounts()
    {
        return Auth()->user()->accounts;
    }

    /**
     * Get account addreses
     *
     * @param Account $account
     *
     * @return [type]
     */
    public function getAccountAddresses(Account $account)
    {
        return $account->address;
    }

    /**
     * Create the user a new adress
     *
     * @param Request $request
     *
     * @return [type]
     */
    public function createAccountAddress(Request $request)
    {
        $account = Account::findOrFail(Request('account_id'));

        $request->validate([
            //'country_id' => ['required'],
            'phone'      => ['required'],
            'postcode'   => ['required'],
            'address'    => ['required'],
            'town'       => ['required'],
            'county'     => ['required'],
        ]);

        $address             = new Address();
        $address->company    = Request('company');
        $address->nickname   = Request('nickname');
        $address->country_id = Request('country_id') ?? 222;
        $address->phone      = Request('phone');
        $address->postcode   = Request('postcode');
        $address->lookup     = Request('lookup');
        $address->address    = Request('address');
        $address->address_2  = Request('address_2');
        $address->town       = Request('town');
        $address->county     = Request('county');
        $address->state      = Request('state');
        $address->lng        = Request('lng');
        $address->lat        = Request('lat');
        $address->distance   = Request('distance');
        $address->save();

        // Sync the address to the account
        $account->address()->attach($address->id);

        return response()->json([
            'status'  => 'success',
            'account' => $account,
            'address' => $address,
        ]);
    }
    /**
     * Update a user's address
     *
     * @param Request $request
     *
     * @return [type]
     */
    public function updateAccountAddress(Request $request)
    {
        $account     = Account::findOrFail(Request('account_id'));
        $old_address = Address::findOrFail(Request('address_id'));

        $request->validate([
            //'country_id' => ['required'],
            'phone'      => ['required'],
            'postcode'   => ['required'],
            'address'    => ['required'],
            'town'       => ['required'],
            'county'     => ['required'],
        ]);

        $address             = new Address();
        $address->company    = Request('company');
        $address->nickname   = Request('nickname');
        $address->country_id = Request('country_id') ?? 222;
        $address->phone      = Request('phone');
        $address->postcode   = Request('postcode');
        $address->lookup     = Request('lookup');
        $address->address    = Request('address');
        $address->address_2  = Request('address_2');
        $address->town       = Request('town');
        $address->county     = Request('county');
        $address->state      = Request('state');
        $address->lng        = Request('lng');
        $address->lat        = Request('lat');
        $address->distance   = Request('distance');
        $address->save();

        $old_address->is_active = false;
        $old_address->save();

        // Sync the address to the account
        $account->address()->attach($address->id);

        return response()->json([
            'status'  => 'success',
            'account' => $account,
            'address' => $address,
        ]);
    }
    /**
     * Delete user's address
     *
     * @param Request $request
     *
     * @return [type]
     */
    public function deleteAccountAddress(Request $request)
    {
        $address = Address::findOrFail(Request('address_id'));

        $address->is_active = false;
        $address->save();

        return response()->json([
            'status'  => 'success'
        ]);
    }


    // 📦📦📦📦📦📦📦📦📦📦📦 USER ORDERS 📦📦📦📦📦📦📦📦📦📦📦📦📦📦📦📦📦📦📦📦📦📦📦📦📦
    /**
     * Get all users orders
     * @return [type]
     */
    public function getUserOrders()
    {
        $pagination = Request('pagination') ?? 10;
        $orderBy    = Request('orderBy') ?? 'created_at';
        $order      = Request('order') ?? 'desc';

        $orders   = Auth()->user()->order()
            ->orderBy($orderBy, $order)
            ->paginate($pagination);

        return OrderResource::collection($orders);
    }

    //🔔🔔🔔🔔🔔🔔🔔🔔🔔🔔🔔🔔NOTIFICATIONS🔔🔔🔔🔔🔔🔔🔔🔔🔔🔔🔔🔔🔔🔔🔔🔔🔔🔔🔔🔔🔔🔔🔔🔔🔔🔔🔔🔔🔔🔔
    /**
     * Get all the user notifications
     * @return [type]
     */
    public function userGetNotification()
    {
        $pagination = Request('pagination') ?? 10;
        switch (Request('action') ?? 'default') {
            case 'read':
                return Auth()->user()->readNotifications()->paginate($pagination);
                break;
            case 'unread':
                return Auth()->user()->unreadNotifications()->paginate($pagination);
                break;
            default:
                return Auth()->user()->notifications()->paginate($pagination);
                break;
        }
    }

    /**
     * Read user notification
     *
     * @param Request $request
     *
     * @return [type]
     */
    public function userReadNotification(Request $request)
    {
        foreach (Request('ids') as $key => $id) {
            $notification = auth()->user()->unreadNotifications->find($id);
            if (!empty($notification)) {
                $notification->markAsRead();
            }
        }

        return $this->respondSuccess('True');
    }

    /**
     * Delete a user notification
     *
     * @param Request $request
     *
     * @return [type]
     */
    public function userDeleteNotification(Request $request)
    {
        foreach (Request('ids') as $key => $id) {
            auth()->user()->notifications()->find($id)->delete();
        }

        return $this->respondSuccess('True');
    }

    /**
     * Test 💩 👻 💀💩 👻 💀💩 👻 💀💩 👻 💀💩 👻 💀💩 👻 💀💩 👻 💀💩 👻💩👻💀
     * @return [type]
     */
    public function sendTestNotification()
    {
        $icons = [
            'success',
            'info',
            'error',
            'warning',
        ];

        $titles = [
            'Hi i am a notification 💩 👻 💀💩 👻 💀💩 👻 💀',
            'Hi this is a notification 💀 ☠️ 👽 👾 🤖',
            'Aliens are invading earth 👽👽👽👽👽👽👽',
            'There is a 👻 watching you right now 🎃🎃🎃',
        ];

        $messages = [
            'Hi i am a example of content',
            'Hi i am a 💀 i come from 👽 👾 🤖',
            'Is the 👽👽👽👽👽👽 time.',
            'Is the 👻 time.',
        ];

        $radonIcon      = rand(1, count($icons));
        $randomtitles   = rand(1, count($titles));
        $randommessages = rand(1, count($messages));

        Auth()->user()->notify(
            new GenericNotification(
                $titles[$randomtitles - 1],
                $icons[$radonIcon - 1],
                $messages[$randommessages - 1]
            )
        );
    }

    //😃😃😃😃😃😃😃😃😃 USER PROFILE😃😃😃😃😃😃😃😃😃😃😃😃😃😃😃😃😃😃😃

    /**
     * Update the user password
     *
     * @param Request $request
     *
     * @return [type]
     */
    public function userPasswordUpdate(Request $request)
    {
        // Validate the user
        $request->validate([
            'email'        => ['required', 'string', 'email', 'max:255'],
            'old_password' => ['required', 'string', 'min:8'],
            //'password'     => ['required', 'confirmed', Password::min(8)->uncompromised()],
            'password'     => ['required', 'confirmed'],
        ]);

        // Check for the old password first
        if (Auth::attempt(['email' => Request('email'), 'password' => Request('old_password')])) {
            Auth()->user()->password = Hash::make(Request('password'));
            Auth()->user()->save();

            return json_encode([
                'status'    => 'success',
                'message' => 'Password Updated',
            ]);
        } else {
            return json_encode([
                'message' => 'wrong user or password',
            ], 401);
        }
    }

    /**
     * Update the user basic profile
     *
     * @param Request $request
     *
     * @return [type]
     */
    public function userProfileUpdate(Request $request)
    {
        // Validate the user
        $request->validate([
            'first_name' => ['required'],
            'last_name'  => ['required'],
            'email'      => 'required|unique:users,email,' . Auth()->user()->id,
        ]);

        Auth()->user()->first_name = Request('first_name');
        Auth()->user()->last_name  = Request('last_name');
        Auth()->user()->email      = Request('email');
        Auth()->user()->save();

        return json_encode([
            'status'    => 'success',
            'message'   => 'Profile Updated',
        ]);
    }

    // USER REVIEWS  🧑🏽‍🦱🧑🏽‍🦱🧑🏽‍🦱🧑🏽‍🦱🧑🏽‍🦱🧑🏽‍🦱
    public function getReviews(Request $request)
    {
        $order_by = $request->input('order_by') ?? 'id';
        $order    = $request->input('order') ?? 'asc';
        $page     = $request->input('page') ?? 1;
        $per_page = $request->input('per_page') ?? 10;

        // Get the review with pagination
        $review = ProductReview::where('user_id', Auth::user()->id)
            ->orderBy($order_by, $order)
            ->paginate($per_page, ['*'], 'page', $page);

        // Get all the user reviews using pagination
        return ProductReviewResource::collection($review);
    }

    /**
     * Add Product Review
     *
     * @param Request $request
     * @param Product $product
     *
     * @return [type]
     */
    public function addReview(Request $request, Product $product)
    {
        $request->validate([
            'rating'  => 'required',
            'comment' => 'required',
        ]);
        // Check if the user has already posted a review
        $review = ProductReview::where('user_id', Auth::user()->id)->where('product_id', Request('product_id'))->first();

        // Check for double review from the same user
        if (!empty($review)) {
            return json_encode([
                'status'    => 'error',
                'message'   => 'You have already reviewed this product.',
            ]);
        }

        $productReview             = new ProductReview();
        $productReview->name       = Auth::user()->first_name;
        $productReview->user_id    = Auth::user()->id;
        $productReview->rate       = Request('rating');
        $productReview->comment    = Request('comment');
        $productReview->product_id = Request('product_id');
        $productReview->save();

        // Return a success message
        return json_encode([
            'status'    => 'success',
            'message'   => 'Review Added with success.',
            'data'      => $productReview
        ]);
    }

    /**
     * Update the user review
     *
     * @param Request $request
     * @param ProductReview $productReview
     *
     * @return [type]
     */
    public function updateReview(Request $request)
    {
        $request->validate([
            'product_id'    => 'required'
        ]);

        $productReview = ProductReview::find($request->input('product_id'));

        // Double-check that the review is by the same user
        if ($productReview->user_id  != Auth::user()->id) {
            return redirect()->back()->with('error', 'Action not valid.');
        }
        if (!empty($request->input('rating'))) {
            $productReview->rate       = $request->input('rating');
        }
        if (!empty($request->input('comment'))) {
            $productReview->comment    = Request('comment');
        }
        $productReview->save();

        // Return a success message
        return json_encode([
            'status'    => 'success',
            'message'   => 'Review updated successfully.',
            'data'      => $productReview
        ]);
    }

    /**
     * Update the user review
     *
     * @param Request $request
     * @param ProductReview $productReview
     *
     * @return [type]
     */
    public function deleteReviews(Request $request)
    {
        $results = [];

        foreach (Request('ids') as $key => $id) {
            $review = ProductReview::find($id);
            if ($review->user_id  != Auth::user()->id) {
                $results[] = array(
                    'id' => $review->id,
                    'status' => 'You are not authorised to delete this review'
                );
            } else {
                $results[] = array(
                    'id' => $review->id,
                    'status' => 'success'
                );
                $review->delete();
            }
        }

        // Return a success message
        return json_encode([
            'status'    => 'success',
            'results'   => $results,
        ]);
    }

    // USER END  🧑🏽‍🦱🧑🏽‍🦱🧑🏽‍🦱🧑🏽‍🦱🧑🏽‍🦱🧑🏽‍🦱
}
