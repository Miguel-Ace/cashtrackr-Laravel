import '@inertiajs/core'

declare module "@inertiajs/core" {
  export interface InertiaConfig {
    sharedPageProps: {
      flash: {
        success?: string
      },
      user: {
        id: number,
        email: string,
        name: string
      }
    }
  }
}