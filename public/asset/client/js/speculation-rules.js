(function () {
    if (typeof HTMLScriptElement === 'undefined' || !('supports' in HTMLScriptElement)) {
        return;
    }

    if (!HTMLScriptElement.supports('speculationrules')) {
        return;
    }

    var rules = {
        prefetch: [
            {
                source: 'document',
                where: {
                    and: [
                        { href_matches: '/*' },
                        { not: { href_matches: '/logout' } },
                        { not: { selector_matches: '[target], [download], [rel~=nofollow], [data-no-speculation]' } }
                    ]
                },
                eagerness: 'moderate'
            }
        ],
        prerender: [
            {
                source: 'document',
                where: {
                    and: [
                        { href_matches: '/*' },
                        { not: { href_matches: '/logout' } },
                        { selector_matches: 'a[href]:not([target]):not([download]):not([rel~=nofollow]):not([data-no-prerender])' }
                    ]
                },
                eagerness: 'conservative'
            }
        ]
    };

    var script = document.createElement('script');
    script.type = 'speculationrules';
    script.textContent = JSON.stringify(rules);
    document.head.appendChild(script);
})();
