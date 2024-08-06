<% cached 'jobstructureddata', $Job.ID %>
    <% with $Job %>
        <script type="application/ld+json">
            {
              "@context" : "https://schema.org/",
              "@type" : "JobPosting",
              "title" : " $Title",
          "description" : "$contentForGoogle.RAW",
          "url": "$AbsoluteLink",
          "identifier": {
            "@type": "PropertyValue",
            "name": "$SiteConfig.Title",
            "value": "$ID"
          },
          "datePosted" : "$LastEdited",
          "validThrough" : "$get_x_months_to_the_future",
          "employmentType" : "$Title",
          "hiringOrganization" : {
            "@type" : "Organization",
            "name" : "$SiteConfig.Title",
            "sameAs" : "$BaseHref",
            "logo" : "$Up.LogoLink"
          },
          "jobLocation": {
          "@type": "Place",
            "address": {
            "@type": "PostalAddress",
            "streetAddress": "$currentAddress",
            "postalCode": "$currentZip",
            "addressRegion": "$currentCity",
            "addressLocality": "$currentAddressLocality",
            "addressCountry": "$currentCountry"
            }
          },
          "baseSalary": {
            "@type": "MonetaryAmount",
            "currency": "Euro",
            "value": {
              "@type": "QuantitativeValue",
              "value": $SalaryPerHour,
              "unitText": "HOUR"
            }
          }
        }
        </script>
    <% end_with %>
<% end_cached %>
