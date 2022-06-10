<template>
    <div>
        <div class="widget-block">
            <label class="chat-label" for="allowed-domains">Domain Restriction</label>
        </div>
        <div class="widget-block">
            <label class="switch">
                <input type="checkbox" v-model="enabled" :disabled="!editable">
                <span class="slider round"></span>
            </label> 
            <span class="chat-label">
                {{ enabled ? "Enabled" : "Disabled" }}
            </span>
        </div>

        <template v-if="enabled">
            <div class="widget-block">
                <label class="chat-label me-auto">Allowed Domains</label>
                <button class="btn btn-light d-flex align-items-center justify-content-between" @click.prevent="addDomainEntry()" :disabled="!editable">
                    <ion-icon name="add-circle-outline"></ion-icon>&nbsp;Domain
                </button>
            </div>

            <div class="widget-block" v-for="(domain, index) in domainsList" :key="index">
                <div class="input-group">
                    <input class="form-control form-control-widget-full" 
                        :class="{ 
                            'is-invalid': !isValidDomain(index), 
                            'is-valid': isValidDomain(index) 
                        }"
                        type="text" 
                        v-model="domainsList[index]" 
                        placeholder="https://sms-chat.ml" 
                        required
                        :disabled="!editable">
                    <button class="btn save-button px-3" @click.prevent="removeDomainAt(index)" :disabled="!editable">
                        <ion-icon name="close-circle-outline"></ion-icon>
                    </button>
                </div>
            </div>

            <div class="widget-block fst-italic flex-column">
                <p class="mb-0">Expected format: </p>
                <p class="mb-0">(http or https)://(domain)(:port, optional)</p>
            </div>

            <input type="hidden" name="allowed_domains" id="allowed-domains" :value="domainsListJson">
        </template>

        <div class="widget-block">
            <p class="chat-label-2 mb-0">By default, the code will work on all the domains and URLs where it has been inserted. To show or hide the widget on one or morer specific domain or URLs, enable this functionality and specify the rule.</p>
        </div>
    </div>
</template>

<script>
const EMPTY_DOMAIN_LIST_JSON = '[""]';

export default {
  props: [
    "editable",
    "initialDomainsListJson"
  ],

  computed: {
        domainsListJson() {
            return JSON.stringify(this.domainsList)
        },
  },

  data() {
    return {
        enabled: this.initialDomainsListJson !== EMPTY_DOMAIN_LIST_JSON,
        domainsList: this.initialDomainsListJson !== EMPTY_DOMAIN_LIST_JSON 
            ? JSON.parse(this.initialDomainsListJson)
            : [""]
    };
  },

  methods: {
        addDomainEntry() {
            this.domainsList.push("");
        },

        removeDomainAt(index) {
            this.domainsList = this.domainsList.filter((_, i) => i !== index);

            if (this.domainsList.length === 0) {
                this.enabled = false;
            }
        },

        isValidDomain(index) {
            return /^https?\:\/\/.+(\:\d+)?$/i.test(this.domainsList[index]);
        }
  }
};
</script>
