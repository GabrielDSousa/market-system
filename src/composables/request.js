import { useAuthStore } from '@/stores/AuthStore'
import { useAlertStore } from '@/stores/AlertStore'

export function useRequest(method, path, body = null, append = null) {
  const baseUrl = `${import.meta.env.VITE_API_URL}`

  const authStore = useAuthStore()
  const alertStore = useAlertStore()

  function setHeader() {
    let myHeaders = new Headers()
    myHeaders.append('Content-Type', 'application/json')

    if (method !== 'GET') {
      myHeaders.append('Authorization', 'Bearer ' + authStore.bearer)
    }

    if (typeof append === 'object') {
      for (append in append) {
        myHeaders.append(append.key, append.value)
      }
    }
    return myHeaders
  }

  function setOptions(headers) {
    let requestOptions = {
      method: method,
      headers: headers,
      redirect: 'follow'
    }
    if (body) {
      requestOptions.body = JSON.stringify(body)
    }
    return requestOptions
  }

  function handleResponse(response) {
    return response.text().then((text) => {
      const data = text && JSON.parse(text)

      if (!response.ok) {
        const { bearer } = useAuthStore()
        if ([401, 403].includes(response.status) && bearer) {
          alertStore.alertError(response.status, 'Your session has expired. Please login again.')
          // auto logout if 401 Unauthorized or 403 Forbidden response returned from api
          useAuthStore().logout()
        }

        alertStore.alertError(response.status, data)
        return Promise.reject(data)
      }

      return data
    })
  }

  let myHeaders = setHeader()

  let requestOptions = setOptions(myHeaders)

  return fetch(baseUrl + path, requestOptions).then((res) => handleResponse(res))
}

export function useGet(path) {
  return useRequest('GET', path)
}

export function usePost(path, body = null) {
  return useRequest('POST', path, body)
}

export function usePut(path, body = null) {
  return useRequest('PUT', path, body)
}

export function useDelete(path, body = null) {
  return useRequest('DELETE', path, body)
}
