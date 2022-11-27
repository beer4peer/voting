<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center justify-between pt-6 sm:pt-0 bg-gray-100">
        <div class="flex flex-col sm:justify-center items-center">
            <div>
                <a href="/">
                    <img src="{{ asset('img/logo.jpg') }}" alt="logo" class="w-24 h-24 rounded-xl">
                </a>
            </div>

            <div class="pt-12">
                <a href="{{ route('login-redirect') }}"
                   style="align-items:center;color:#000;background-color:#fff;border:1px solid #ddd;border-radius:4px;display:inline-flex;font-family:Lato, sans-serif;font-size:18px;font-weight:600;height:56px;justify-content:center;text-decoration:none;width:296px">
                    <svg xmlns="http://www.w3.org/2000/svg" style="height:24px;width:24px;margin-right:12px"
                         viewBox="0 0 122.8 122.8">
                        <path
                            d="M25.8 77.6c0 7.1-5.8 12.9-12.9 12.9S0 84.7 0 77.6s5.8-12.9 12.9-12.9h12.9v12.9zm6.5 0c0-7.1 5.8-12.9 12.9-12.9s12.9 5.8 12.9 12.9v32.3c0 7.1-5.8 12.9-12.9 12.9s-12.9-5.8-12.9-12.9V77.6z"
                            fill="#e01e5a"></path>
                        <path
                            d="M45.2 25.8c-7.1 0-12.9-5.8-12.9-12.9S38.1 0 45.2 0s12.9 5.8 12.9 12.9v12.9H45.2zm0 6.5c7.1 0 12.9 5.8 12.9 12.9s-5.8 12.9-12.9 12.9H12.9C5.8 58.1 0 52.3 0 45.2s5.8-12.9 12.9-12.9h32.3z"
                            fill="#36c5f0"></path>
                        <path
                            d="M97 45.2c0-7.1 5.8-12.9 12.9-12.9s12.9 5.8 12.9 12.9-5.8 12.9-12.9 12.9H97V45.2zm-6.5 0c0 7.1-5.8 12.9-12.9 12.9s-12.9-5.8-12.9-12.9V12.9C64.7 5.8 70.5 0 77.6 0s12.9 5.8 12.9 12.9v32.3z"
                            fill="#2eb67d"></path>
                        <path
                            d="M77.6 97c7.1 0 12.9 5.8 12.9 12.9s-5.8 12.9-12.9 12.9-12.9-5.8-12.9-12.9V97h12.9zm0-6.5c-7.1 0-12.9-5.8-12.9-12.9s5.8-12.9 12.9-12.9h32.3c7.1 0 12.9 5.8 12.9 12.9s-5.8 12.9-12.9 12.9H77.6z"
                            fill="#ecb22e"></path>
                    </svg>
                    Sign in with Slack</a>
            </div>
        </div>

        <div class="text-xs text-gray-700 max-w-xl pt-8 text-justify p-2">
            <p>If you can't link your account using this link, don't panic - it just means that your BROWSER sessions
                are not logged into the same accounts as your App sessions. To fix it, just <a
                    href="https://slack.com/intl/en-au/signin#/signin" target="_blank">go here</a>, log in with the
                account that you have associated with your b4p session, and then click on the B4P slack when you see it.
                That will open either an in-browser slack, or re-auth your application slack. After that, come back
                to this window and authenticate again.</p>
            <p>&nbsp;</p>
            <p><a href="https://slack.com/intl/en-au/signin#/signin" target="_blank">https://slack.com/intl/en-au/signin#/signin</a>
            </p>
            <p>&nbsp;</p>

            <figure>
                <a href="https://slack.com/intl/en-au/signin#/signin">
                    <img src="{{ asset('img/slack-example.png') }}" alt="Slack Example"/>
                    <figcaption>This is an example of what  you will see if you are xrobau and click on the link above, you can try clicking it - but it will do nothing.</figcaption>
                </a>
            </figure>
        </div>
    </div>
</x-guest-layout>
