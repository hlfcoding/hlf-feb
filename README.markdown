The FrontEnd Builds `FEB` Framework 
        
In the world of agency-client web design, a common practice is to design a bunch of PSD comps and to create HTML/CSS versions for each and every one of those images. The typical result is unmanageable, entangled markup soup that do not follow the don't-repeat-yourself and separation-of-concerns best practices of modern web development. But it's impractical to use a comprehensive PHP framework like Cake or CodeIgniter when are you need is a semi-working set of static pages that still allow for best practices. Your team may also not be doing the actual backend development, so you can't safely bet on a server, language, and framework. The problem does not become apparent until you start maintaining 15+ unique HTML/CSS builds through revision phases.

Enter the FEB Framework:
        
*   for when you just need to get the frontend done and  the backend or official framework solution is unknown
*   separation of content and presentation
*   linked builds pages
*   DRY refactoring of presentation, but remaining flexible
*   easy-to-understand design pattern, convention over configuration
*   minimal PHP boilerplate, overhead, libraries, etc.
*   relatively pretty urls
*   easy to export final HTML builds
*   totally duct-taped php code, feel free to expand on your own