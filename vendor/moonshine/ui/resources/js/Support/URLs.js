export function excludeFromParams(params, exclude = null) {
  if (exclude !== null) {
    const excludes = exclude.split(',')

    excludes.forEach(function (excludeName) {
      params.delete(excludeName)
    })
  }

  return params
}

export function limitQueryParams(params, maxLength = 50) {
  const filtered = new URLSearchParams()

  for (const [key, value] of params) {
    if (value.length <= maxLength) {
      filtered.append(key, value)
    }
  }

  return filtered
}

export function prepareQueryParams(params, exclude = null) {
  return new URLSearchParams(excludeFromParams(params, exclude))
}

export function mergeURLString(url, merge) {
  if (merge === '') {
    return url
  }

  return url + (url.includes('?') ? '&' + merge : '?' + merge)
}
