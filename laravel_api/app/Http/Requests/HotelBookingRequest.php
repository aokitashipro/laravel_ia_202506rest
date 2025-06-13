<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HotelBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * バリデーションエラー時のレスポンス形式をカスタマイズ
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        \Log::info('=== ホテル予約バリデーション失敗 ===');
        \Log::info('バリデーションエラー:', [
            'input_data' => $this->all(),
            'errors' => $validator->errors()->toArray()
        ]);

        throw new \Illuminate\Validation\ValidationException($validator, response()->json([
            'message' => '入力データに誤りがあります。',
            'errors' => $validator->errors()
        ], 422));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        \Log::info('=== ホテル予約バリデーション開始 ===');
        return [
            'guest_name' => 'required|string|max:100',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'checkin_date' => 'required|date|after_or_equal:today',
            'checkout_date' => 'required|date|after:checkin_date',
            'guest_count' => 'required|integer|min:1|max:10',
            'room_type' => 'required|in:シングル,ダブル,ツイン,スイート,ファミリー',
            'special_requests' => 'nullable|string|max:500'
        ];
    }

    public function messages(): array
    {
        return [
            'checkin_date.after_or_equal' => 'チェックイン日は本日以降の日付を指定してください。',
            'checkout_date.after' => 'チェックアウト日はチェックイン日より後の日付を指定してください。',
            'room_type.in' => '指定された部屋タイプは利用できません。',
            'guest_count.min' => '宿泊人数は1人以上を指定してください。',
            'guest_count.max' => '宿泊人数は最大10人までです。'
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            \Log::info('バリデーションエラー:', $validator->errors()->toArray());

            $checkin = $this->input('checkin_date');
            $checkout = $this->input('checkout_date');
            $roomType = $this->input('room_type');
            $guestCount = $this->input('guest_count');

            // 1. 宿泊日数チェック（最大30日）
            if ($checkin && $checkout) {
                $days = \Carbon\Carbon::parse($checkin)->diffInDays(\Carbon\Carbon::parse($checkout));
                if ($days > 30) {
                    $validator->errors()->add('checkout_date', '宿泊期間は最大30日までです。');
                }
            }

            // 2. 部屋タイプと宿泊人数の整合性チェック
            if ($roomType && $guestCount) {
                $maxGuests = [
                    'シングル' => 1,
                    'ダブル' => 2,
                    'ツイン' => 2,
                    'スイート' => 4,
                    'ファミリー' => 6
                ];

                if ($guestCount > $maxGuests[$roomType]) {
                    $validator->errors()->add('guest_count', $roomType . 'ルームの最大宿泊人数は' . $maxGuests[$roomType] . '人までです。');
                }
            }

            // 3. 土日の予約制限（金曜・土曜チェックインは3日前まで）
            if ($checkin) {
                $checkinDate = \Carbon\Carbon::parse($checkin);
                if (in_array($checkinDate->dayOfWeek, [5, 6])) { // 金曜日(5), 土曜日(6)
                    if ($checkinDate->diffInDays(now()) < 3) {
                        $validator->errors()->add('checkin_date', '金曜・土曜のチェックインは3日前までにご予約ください。');
                    }
                }
            }
        });
    }
}
