package com.transformers.easycash.easycash;

import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.graphics.Typeface;
import android.preference.PreferenceManager;
import android.support.design.widget.Snackbar;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.EditText;
import android.widget.TextView;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;

public class login extends AppCompatActivity {
    EditText pswd, mail;
    TextView sup, lin;
    private static final String RESPONSELOG = "LogResponse";
    RequestQueue queue;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);
        queue = Volley.newRequestQueue(this);

        lin = (TextView) findViewById(R.id.lin);
        mail = (EditText) findViewById(R.id.mail);
        pswd = (EditText) findViewById(R.id.pswrdd);
        sup = (TextView) findViewById(R.id.sup);
        Typeface custom_font = Typeface.createFromAsset(getAssets(), "fonts/LatoLight.ttf");
        Typeface custom_font1 = Typeface.createFromAsset(getAssets(), "fonts/LatoRegular.ttf");
        lin.setTypeface(custom_font1);
        sup.setTypeface(custom_font);
        mail.setTypeface(custom_font);
        pswd.setTypeface(custom_font);

        lin.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
//                Intent it = new Intent(login.this, signup.class);
//                startActivity(it);
                if (mail.getText().toString().isEmpty() || mail.getText().toString() == null) {
                    mail.setError("Email is required");
                } else if (pswd.getText().toString().isEmpty() || pswd.getText().toString() == null) {
                    pswd.setError("Password is required");
                } else {

                    String url = "http://api.recodenigeria.tk/api/v1/customer/login";
                    HashMap<String, String> params = new HashMap<String, String>();
                    params.put("email", mail.getText().toString());
                    params.put("password", pswd.getText().toString());
                    JSONObject jsonBody = new JSONObject(params);
                    boolean status = false;
                    JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.POST, url, jsonBody, new Response.Listener<JSONObject>() {
                        @Override
                        public void onResponse(JSONObject response) {
                            Log.i(RESPONSELOG, response.toString());
                            Intent it = new Intent(login.this, DashBoardActivity.class);
                            startActivity(it);

                        }
                    }, new Response.ErrorListener() {
                        @Override
                        public void onErrorResponse(VolleyError error) {
                            Log.i(RESPONSELOG, error.toString());
                            Snackbar.make(findViewById(R.id.loginCoordLayout), "Invalid email or password", Snackbar.LENGTH_SHORT).show();
                        }
                    });
                    queue.add(jsonObjectRequest);
                }
            }
        });

        sup.setLinksClickable(true);
        sup.setLinkTextColor(Color.BLUE);
        sup.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent it = new Intent(login.this, signup.class);
                startActivity(it);
            }
        });
    }
}
