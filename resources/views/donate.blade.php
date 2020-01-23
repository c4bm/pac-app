@extends('layout')
@section('top-scripts')
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <style>
        [v-cloak] {
            opacity: 0;
        }
    </style>
@endsection

@section('content')
    <a href="/">
        <h1 class="text-xl font-semibold text-white uppercase tracking-wider text-center">Coalition for Better Montgomery</h1>
    </a>

    <div id="donation-app" v-cloak class="border-white bg-white rounded-lg p-4 mt-8">
        <div class="flex justify-between">
            <div class="mr-2">
                <button class="text-center block border py-1 md:py-2 px-1 md:px-4 cursor-pointer"
                        :class="step === 1 ? currentStepClass : otherStepClass"
                        @click="setStep(1)"
                >Amount @{{ ': $' + donationData.amount }}</button>
            </div>
            <div class="mr-2">
                <button class="text-center block border py-1 md:py-2 px-1 md:px-4 cursor-pointer"
                        :class="(step === 2 ? currentStepClass : otherStepClass) + (amountOk(this.donationData) ? '' : ' disabled cursor-not-allowed opacity-50')"
                        @click="setStep(2)"
                        :disabled="!amountOk(this.donationData)"
                >Donor Info</button>
            </div>
            <div class="text-center">
                <button class="text-center block border py-1 md:py-2 px-1 md:px-4 cursor-pointer"
                        :class="(step === 3 ? currentStepClass : otherStepClass) + (canPay ? '' : ' disabled cursor-not-allowed opacity-50')"
                        @click="setStep(3)"
                        :disabled="!canPay"
                >Payment</button>
            </div>
        </div>

        <hr class="my-2">
        <div style="min-height: 300px;" class="flex flex-col justify-between">
            <div style="max-width: 650px;">
                <div :class="{ 'hidden': step !== 1 }">
                    <button class="border-blue-500 bg-blue-500 w-16 mr-2 p-1 md:p-2 text-white font-bold cursor-pointer mb-2" v-for="amount in [10, 25, 50, 100, 200]" @click="setAmount(amount)">
                        $ @{{ amount }}
                    </button>
                    <span class="inline-block mr-2 italic">OR</span>
                    <div class="inline-block">
                        $ <input type="number" v-model="donationData.amount" class="border text-right py-2 px-1 w-16">
                    </div>
                    <div>(Please select or enter an amount above)</div>
                    <!-- dummy text for width control -->
                    <p class="opacity-0">This contribution is made from my own funds, and funds are not being provided to me by another person or entity for the purpose of making this contribution.</p>
                </div>
                <div :class="{ 'hidden': step !== 2 }" class="px-2">
                    <!-- EMAIL -->
                    <label class="flex mb-2">
                        <div :class="labelClass">
                            Email
                        </div>
                        <div class="w-full md:w-2/3">
                            <input type="email" v-model="donationData.email" placeholder="Email" :class="generalInputClass">
                            <div class="text-xs text-red-500 ml-2">@{{ errors.email }}</div>
                        </div>
                    </label>

                    <div class="flex mb-2">
                        <div :class="labelClass">
                            Name
                        </div>
                        <div class="w-full md:w-2/3 flex">
                            <div class="w-1/2 mr-1">
                                <label for="firstName" class="hidden">First Name</label>
                                <input type="text" v-model="donationData.firstName" placeholder="First Name" :class="generalInputClass" id="firstName">
                                <div class="text-xs text-red-500 ml-2">@{{ errors.firstName }}</div>
                            </div>
                            <div class="w-1/2 ml-1">
                                <label for="lastName" class="hidden">Last Name</label>
                                <input type="text" v-model="donationData.lastName" placeholder="Last Name" :class="generalInputClass" id="lastName">
                                <div class="text-xs text-red-500 ml-2">@{{ errors.lastName }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Address 1, address 2, city -->
                    <div class="flex mb-2" v-for="field in fields">
                        <label :class="labelClass" :for="field.name">
                            @{{ field.label }}
                        </label>
                        <div class="w-full md:w-2/3">
                            <input :type="field.type" v-model="donationData[field.name]" :placeholder="field.label" :class="generalInputClass" :id="field.name">
                            <div class="text-xs text-red-500 ml-2">@{{ errors[field.name] }}</div>
                        </div>
                    </div>

                    <!-- state & zip -->

                    <div class="flex mb-2">
                        <div :class="labelClass">
                            State/Zip
                        </div>
                        <div class="w-full md:w-2/3 flex">
                            <div class="w-1/2 mr-1">
                                <label class="hidden" for="state">State</label>
                                <select v-model="donationData.state"  :class="generalInputClass + ' w-full'" id="state">
                                    <option v-for="state in states" :value="state">@{{ state }}</option>
                                </select>
                            </div>
                            <div class="w-1/2 ml-1">
                                <label class="hidden" for="zip">Zip</label>
                                <input type="text" v-model="donationData.postalCode" placeholder="Postal Code" :class="generalInputClass" id="zip">
                                <div class="text-xs text-red-500 ml-2">@{{ errors.postalCode }}</div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-2">

                    <!-- employment -->
                    <label class="flex -mb-2">
                        <div :class="labelClass"></div>
                        <div class="w-full md:w-2/3">
                            <input type="checkbox" v-model="donationData.employed" class="mr-2">I'm employed
                        </div>
                    </label>
                    <label class="flex mb-2" :class="{ 'hidden' : !donationData.employed }" >
                        <div :class="labelClass">
                            Employer
                        </div>

                        <div class="w-full md:w-2/3">
                            <input type="text" v-model="donationData.employer" placeholder="Employer" :class="generalInputClass">
                            <div class="text-xs text-red-500 ml-2">@{{ errors.employer }}</div>
                        </div>
                    </label>

                    <hr class="my-2">

                    <!-- FEC requirements -->
                    <div class="mb-4">
                        <label class="font-bold">
                            <input type="checkbox" v-model="donationData.certifiedStatement" class="mr-2">I certify that:
                        </label>
                        <ul class="list-disc px-6 text-sm">
                            <li>I am a U.S. citizen or lawfully admitted permanent resident (i.e., green card holder).</li>
                            <li>This contribution is made from my own funds, and funds are not being provided to me by another person or entity for the purpose of making this contribution.</li>
                            <li>I am making this contribution with my own personal credit/debit card (or PayPal account), and not with a corporate or business credit card (or PayPal account) or a card issued to (or a Paypal account for) another person.</li>
                            <li>I am at least eighteen years old.</li>
                            <li>I am not a federal contractor.</li>
                        </ul>
                    </div>
                </div>
                <div :class="{ 'hidden': step !== 3 }" class="flex flex-col">
                    <div id="paypal-button-container" style="min-width: 250px; max-width: 450px;" class="m-auto md:pt-4"></div>
                    <!-- dummy text for width control -->
                    <p class="opacity-0">This contribution is made from my own funds, and funds are not being provided to me by another person or entity for the purpose of making this contribution.</p>
                </div>
                <div>
                    <button v-if="step === 1"
                            class="border p-2 w-full bg-green-500 text-white"
                            :class="{ 'disabled cursor-not-allowed opacity-50' : !amountOk(this.donationData) }"
                            :disabled="!amountOk(this.donationData)"
                            @click="setStep(2)"
                    >
                        <span class="font-bold">Next:</span> Donor Info
                    </button>
                    <div v-else-if="step === 2" class="flex">
                        <button class="border p-2 w-full bg-gray-500 text-white"
                                @click="setStep(1)"
                        >
                            <span class="font-bold">Back:</span> Amount
                        </button>
                        <button
                            class="border p-2 w-full bg-green-500 text-white"
                            :class="{ 'disabled cursor-not-allowed opacity-50' : !canPay }"
                            :disabled="!canPay"
                            @click="setStep(3)"
                        >
                            <span class="font-bold">Next:</span> Payment
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('bottom-scripts')
    <script>
        let laravel_csrf = '{{ csrf_token() }}'
    </script>
    <script src="https://www.paypal.com/sdk/js?client-id=AeGQA6jpKc5gLRj0Zl6Uc4VzK8fA0iqGCXgNeOOX0rfRJdWiV7b-Fg7NXwoc2qT0sGabVGoVUbo1S_D8&currency=USD" data-sdk-integration-source="button-factory"></script>
    <script>
        var app = new Vue({
            el: '#donation-app',
            data: {
                step: 1,
                currentStepClass: 'border-blue-500 bg-blue-500 hover:bg-blue-700 text-white',
                otherStepClass: 'border-white hover:border-gray-200 text-blue-500 hover:bg-gray-200',
                generalInputClass: 'h-10 py-2 px-4 rounded border border-gray-500 w-full',
                labelClass: 'w-0 md:w-1/3 h-10 py-2 invisible md:visible',
                donationData: {
                    amount: 0,
                    firstName: '',
                    lastName: '',
                    address1: '',
                    address2: '',
                    city: '',
                    state: 'MD',
                    email: '',
                    postalCode: '',
                    mobilePhoneNumber: '',
                    employed: false,
                    employer: '',
                    certifiedStatement: false,
                    _token: laravel_csrf
                },
                canPay: false,
                paymentMethod: 'Paypal',
                fields: [
                    {
                        label: 'Address 1',
                        name: 'address1',
                        type: 'text'
                    },
                    {
                        label: 'Address 2',
                        name: 'address2',
                        type: 'text'
                    },
                    {
                        label: 'City',
                        name: 'city',
                        type: 'text'
                    }
                ],
                states: [
                    "AK", "AL", "AR", "AZ", "CA", "CO", "CT", "DC", "DE",
                    "FL", "GA", "HI", "IA", "ID", "IL", "IN", "KS", "KY",
                    "LA", "MA", "MD", "ME", "MI", "MN", "MO", "MS", "MT",
                    "NC", "ND", "NE", "NH", "NJ", "NM", "NV", "NY",
                    "OH", "OK", "OR", "PA", "RI", "SC", "SD", "TN", "TX",
                    "UT", "VA", "VT", "WA", "WI", "WV", "WY"
                ],
                errors: {}
            },
            methods: {
                setStep(step) {
                    this.step = step
                },
                setAmount(amount) {
                    this.donationData.amount = amount
                },
                getAmount() {
                    return this.donationData.amount
                },
                getPayerInfo() {
                    // Assemble information to pre-fill credit card form
                    let donationData = this.donationData,
                        payerInfo = {
                            name: {
                                given_name: donationData.firstName,
                                surname: donationData.lastName
                            },
                            address: {
                                postal_code: donationData.postalCode,
                                country_code: 'US'
                            }
                        }

                    if (this.mobilePhoneNumberOk(donationData.mobilePhoneNumber)) {
                        payerInfo.phone = {
                            phone_type: "MOBILE",
                            phone_number: {
                                national_number: donationData.mobilePhoneNumber
                            }
                        }
                    }

                    if (this.emailOk(donationData.email)) {
                        payerInfo.email_address = donationData.email
                    }

                    return payerInfo
                },
                paymentConfirmed(data) {
                    if(data.status !== 'COMPLETED') {
                        // TODO what else to do ?
                        return
                    }

                    let form = document.createElement("form")

                    form.method = "POST"
                    form.action = "/donate"

                    Object.keys(this.donationData).forEach((key) => {
                        let el = document.createElement("input")
                        el.type = 'hidden'
                        el.name = key
                        el.value = this.donationData[key]
                        form.appendChild(el);
                    })

                    document.body.appendChild(form)

                    form.submit();
                },
                amountOk(donationData) {
                    return donationData && donationData.amount >= 1
                },
                emailOk(email) {
                    // TODO validate email address
                    return /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/i.test(email)
                },
                mobilePhoneNumberOk(mobilePhoneNumber) {
                    // TODO validate mobile phone number
                    return /^(1\s?)?((\([0-9]{3}\))|[0-9]{3})[\s\-]?[\0-9]{3}[\s\-]?[0-9]{4}$/g.test(mobilePhoneNumber)
                },
                postalCodeOk(postalCode) {
                    return /^\d{5}$|^\d{5}-\d{4}$/.test(postalCode)
                },
                validateDonorInfo(donationData) {
                    const someFields = ['firstName', 'lastName', 'address1', 'city', 'state']

                    if(!this.emailOk(donationData.email.trim())) {
                        this.errors['email'] = 'Email not provided or invalid!'
                        return false
                    } else {
                        delete this.errors['email']
                    }

                    for(let i = 0; i < 4; i++) {
                        if (!(donationData[someFields[i]].trim())) {
                            this.errors[someFields[i]] = 'Field data required!'
                            return false
                        } else {
                            delete this.errors[someFields[i]]
                        }
                    }

                    if(!this.postalCodeOk(donationData.postalCode.trim())) {
                        this.errors['postalCode'] = 'Postal code not provided or invalid!'
                        return false
                    } else {
                        delete this.errors['postalCode']
                    }

                    if (!this.mobilePhoneNumberOk(donationData.mobilePhoneNumber.trim()) && donationData.mobilePhoneNumber.trim()) {
                        this.errors['mobilePhoneNumber'] = 'Mobile phone number provided but invalid!'
                        return false
                    } else {
                        delete this.errors['mobilePhoneNumber']
                    }

                    if(donationData.employed && !(donationData.employer.trim())) {
                        this.errors['employer'] = 'If employed, your employer must not be empty!'
                        return false
                    } else {
                        delete this.errors['employer']
                    }

                    if(!donationData.certifiedStatement) {
                        this.errors['certifiedStatement'] = 'You must certify that you meet the FEC requirements to make a political contribution!'
                        return false
                    } else {
                        delete this.errors['certifiedStatement']
                    }

                    return true

                    /*return donationData.firstName.trim() && donationData.lastName.trim() &&
                        donationData.address1.trim() && donationData.city.trim() && donationData.state &&
                        this.emailOk(donationData.email.trim()) &&
                        this.postalCodeOk(donationData.postalCode.trim()) &&
                        (this.mobilePhoneNumberOk(donationData.mobilePhoneNumber.trim()) || !donationData.mobilePhoneNumber.trim()) &&
                        ((donationData.employed && donationData.employer.trim()) || !donationData.employed) &&
                        donationData.certifiedStatement*/
                },
                validateDonationData(donationData) {
                    this.canPay = this.amountOk(donationData) && this.validateDonorInfo(donationData)
                }
            },
            watch: {
                donationData: {
                    handler: function(newData, oldData) {
                        this.validateDonationData(newData)
                    },
                    deep: true
                }
            }
        })

        paypal.Buttons({
            style: {
                shape: 'pill',
                color: 'blue',
                layout: 'vertical',
                label: 'pay',
            },
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: app.getAmount()
                        }
                    }],
                    application_context: {
                        shipping_preference: 'NO_SHIPPING'
                    },
                    payer: app.getPayerInfo()
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(
                    function(details) {
                        app.paymentConfirmed(details)
                    }
                );
            }
        }).render('#paypal-button-container')
    </script>
@endsection
