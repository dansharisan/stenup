export const DOMUtils = {
    methods: {
        togglePasswordVisibility(event) {
            let $currentTarget = $(event.currentTarget)
            let $input = $currentTarget.closest('.input-group').find('input').first()
            if ($input.attr('type') == 'password') {
                // Unhide the password
                $input.attr('type', 'text')
                // Change the icon
                $currentTarget.find('svg').toggleClass('fa-eye-slash fa-eye')
            } else {
                // Hide the password
                $input.attr('type', 'password')
                // Change the icon
                $currentTarget.find('svg').toggleClass('fa-eye-slash fa-eye')
            }
        }
    }
}
